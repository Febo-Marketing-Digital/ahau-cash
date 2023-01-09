<?php

namespace App\Http\Controllers;

use App\Enums\LoanTypeEnum;
use App\Models\Loan;
use App\Models\User;
use App\Models\LoanInstallment;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    public function index() 
    {
        if (auth()->user()->type == 'staff') {
            $loans = Loan::with('installments')->where('created_by', auth()->user()->id)->latest()->paginate(25);
        } else {
            $loans = Loan::with('installments')->latest()->paginate(25);
        }

        return view('loan.index', compact('loans'));
    }

    public function create(Request $request)
    {
        if ($request->has('client_id')) {
            $clients = User::where('id', $request->get('client_id'))->get();
        } else {
            $allCLients = User::with('address', 'bankDetails')->where('type', 'client')->get();

            $clients = $allCLients->reject(function($client) {
                return is_null($client->address) or is_null($client->bankDetails);
            });
        }

        return view('loan.create', compact('clients'));
    }

    // TODO: los prestamos creados por un usuario no admin, deben ser aprobados antes de estar activos.
    public function store(Request $request)
    {
        $request->validate([
            'loan_type' => 'required',
            'amount' => ['required', 'min:3'],
            'start_date' => ['required', 'date'],
            'installment_period' => ['required', 'in:MONTHLY,BIWEEKLY'],
            'installment_quantity' => ['required', 'numeric'],
            'loan_roi' => ['required', 'integer'],
        ]);

        try {
            DB::beginTransaction();

            //LoanTypeEnum::from($request->loan_type)); // Objeto de tipo Enum con value and label

            // se crea el prestamo en status pendiente de activacion
            $loan = Loan::create([
                'uuid' => Str::uuid(),
                'user_id' => $request->client,
                'type' => $request->loan_type,
                'amount' => $request->amount,
                'duration_unit' => $request->installment_quantity, // 12
                'duration_period' => 'MONTH', // $request->installment_period: TODO cambiar este campo o crear uno nuevo
                'roi' => $request->loan_roi,
                'installment_period' => $request->installment_period,
                'status' => auth()->user()->type == 'admin' ? 'APPROVED' : 'PENDING',
                'created_by' => auth()->user()->id,
            ]);
    
            // se crea la tabla de amortizacion
            $amount = $request->amount;
            $percentage = ($request->loan_roi / 100) * $amount;
            $finalBalance = $amount + $percentage;
            $installmentAmount = $finalBalance / $request->installment_quantity;

            $daysBy = $request->installment_period == 'MONTHLY' ? 30 : 15;
            $startDate = \Carbon\Carbon::parse($request->start_date);
            $endDate = $startDate->copy()->addDays($daysBy);

            $installments = [];

            for ($i = 1; $i <= $request->installment_quantity; $i++) {

                if ($installmentAmount > $finalBalance) {
                    $installmentAmount = $finalBalance;
                }

                // Calculate dates
                if ($i > 1) {
                    $startDate = $endDate;
                    $endDate = $startDate->copy()->addDays($daysBy);
                }

                // substract current installment from final balance (pending amount to paid)
                $finalBalance = $finalBalance - $installmentAmount;

                $installment = LoanInstallment::create([
                    'loan_id' => $loan->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'amount' => $installmentAmount,
                    'balance' => $finalBalance,
                ]);

                $installments[] = $installment;
            }   

            // se crea el PDF
            $this->generatePdfs($loan, $installments);

            DB::commit();

            return redirect(route('loan.index'));
            
        } catch (Exception $e) {
            dump($e->getMessage());
            DB::rollBack();
        }
    }

    public function show(string $uuid) 
    {
        setlocale(LC_TIME, 'es_ES');
        
        $loan = Loan::where('uuid', $uuid)->firstOrFail();

        return view('loan.show', compact('loan'));
    }

    public function destroy(Loan $loan)
    {
        if (auth()->user()->type == 'admin') {
            // valida si el prestamo no esta en proceso???
            
            $installments = LoanInstallment::where('loan_id', $loan->id)->get();
            foreach($installments as $installment) {
                $installment->delete();
            }
            $loan->delete();
            
            // ó
            //$loan->deletePreservingMedia(); // all associated files will be preserved 
        }

        return redirect(route('loan.index'));
    }

    public function update(Request $request, Loan $loan)
    {
        $loan->status = $request->status;
        $loan->save();

        if ($request->status == 'DECLINED') {
            $this->destroy($loan);
        }

        return redirect(route('loan.index'));
    }

    public function settled(Loan $loan)
    {
        if (auth()->user()->type == 'admin') {
            $loan->status = 'SETTLED';
            $loan->save();
        }

        return redirect(route('loan.index'));
    }

    private function generatePdfs(Loan $loan, array $installments)
    {
        // 1 .- se genera el contrato
        $data = [
            'client_fullname' => $loan->user->fullname(),
            'client_address' => $loan->user->address->completeAddress(),
            'installments' => $loan->installments,
            'loan_amount' => $loan->amount,
            'loan_roi' => ($loan->roi / 100) * $loan->amount,
            'loan_date' => $loan->installments->first()->start_date, // FIX THIS
            'account_number' => $loan->user->bankDetails->account_number,
            'account_holder_name' => $loan->user->bankDetails->name . ' ' . $loan->user->bankDetails->lastname,
            'bank_name' => $loan->user->bankDetails->bank_name,
            'installments_total' => $loan->installments->count(),
        ];
        $pdf = Pdf::loadView('loan.pdf.contract_note', $data);
        $pdf->save(storage_path('loans/'.$loan->uuid . "-contract.pdf"));

        $loan
            ->addMedia(storage_path('loans/'.$loan->uuid . "-contract.pdf"))
            ->withCustomProperties(['note_type' => 'contract_note', 'note_name' => 'Contrato'])
            ->toMediaCollection('notes');

        // 2 .- se genera el pagare del monto prestado

        $data = [
            'loan_amount' => $loan->amount,
            'loan_amunt_in_words' => '',
            'roi' => $loan->roi . '%',
            'fullname' => $loan->user->fullname(),
            'user' => $loan->user->toArray(),
            'address' => $loan->user->address,
            'created_date' => now()->format('d M Y'), // change this
            'year_to_convert' => now()->format('Y'),
        ];
    
        $pdf = Pdf::loadView('loan.pdf.loan_note', $data);
        $pdf->save(storage_path('loans/'.$loan->uuid . "-total.pdf"));

        $loan
            ->addMedia(storage_path('loans/'.$loan->uuid . "-total.pdf"))
            ->withCustomProperties(['note_type' => 'total_amount_note', 'note_name' => 'Pagaré Total'])
            ->toMediaCollection('notes');

        // 3 .- se genera pagare de los intereses totales
        $data = [
            'loan_insterest_amount' => ($loan->roi / 100) * $loan->amount,
            'roi' => $loan->roi . '%',
            'fullname' => $loan->user->fullname(),
            'user' => $loan->user->toArray(),
            'address' => $loan->user->address,
            'created_date' => now()->format('d M Y'), // change this
            'year_to_convert' => now()->format('Y'),
        ];

        $pdf = Pdf::loadView('loan.pdf.interest_note', $data);
        $pdf->save(storage_path('loans/'.$loan->uuid . "-interest.pdf"));

        $loan
            ->addMedia(storage_path('loans/'.$loan->uuid . "-interest.pdf"))
            ->withCustomProperties(['note_type' => 'total_interests_note', 'note_name' => 'Pagaré Intereses'])
            ->toMediaCollection('notes');

        // 4 .- se generan todos los pagares de los pagos mensuales o quincenales
        foreach($installments as $key => $installment) {
            $data = [
                'loan_installment_amount' => $installment->amount,
                'roi' => $loan->roi . '%',
                'fullname' => $loan->user->fullname(),
                'user' => $loan->user->toArray(),
                'address' => $loan->user->address,
                'end_date' => $installment->end_date,
            ];

            $pdfInstallment = Pdf::loadView('loan.pdf.installment_note', $data);
            $pdfInstallment->save(storage_path('loans/'.$loan->uuid . "-" . $key . "-.pdf"));

            $installment
                ->addMedia(storage_path('loans/'.$loan->uuid . "-" . $key . "-.pdf"))
                ->withCustomProperties(['note_type' => 'installment_note', 'note_name' => 'Pagaré ' . $key])
                ->toMediaCollection('notes');
        }
    }
}
