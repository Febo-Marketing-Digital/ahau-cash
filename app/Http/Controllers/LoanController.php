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
        return view('loan.index', [
            'loans' => Loan::with('installments')->get(),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->has('client_id')) {
            $clients = User::where('id', $request->get('client_id'))->get();
        } else {
            $clients = User::where('type', 'client')->get();
        }

        return view('loan.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
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
                'duration_unit' => 12, //$request->loan_duration['unit'],
                'duration_period' => 'MONTH', //$request->loan_duration['period'],
                'roi' => $request->loan_roi,
                'installment_period' => $request->installment_period,
                'status' => 'PENDING',
            ]);
    
            // se crea la tabla de amortizacion
            $amount = $request->amount;
            $percentage = ($request->loan_roi / 100) * $amount;
            $finalBalance = $amount + $percentage;
            $installmentAmount = $finalBalance / $request->installment_quantity;

            for ($i = 1; $i <= $request->installment_quantity; $i++) {

                if ($installmentAmount > $finalBalance) {
                    $installmentAmount = $finalBalance;
                }

                $startDate = now(); // fecha del pago
                $endDate = now(); // fecha vencimiento del pago

                // substract current installment from final balance (pending amount to paid)
                $finalBalance = $finalBalance - $installmentAmount;

                LoanInstallment::create([
                    'loan_id' => $loan->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'amount' => $installmentAmount,
                    'balance' => $finalBalance,
                ]);
            }   

            // se crea el PDF
            $user = User::find($request->client);

            $data = [
                'titulo' => 'Ahau Cash',
                'loan_amount' => $loan->amount,
                'roi' => $loan->roi . '%',
                'payment_amount' => $amount + $percentage,
                'user' => $user->toArray(),
            ];
        
            $pdf = Pdf::loadView('loan.pdf.test', $data);
            $pdf->save($loan->uuid);
    
            // se cambia status a activado (pues ya se asume que la vigencia inicia)

            DB::commit();

            return redirect(route('loan.index'));
            
        } catch (Exception $e) {
            dump($e->getMessage());
            DB::rollBack();
        }
    }

    public function show(string $uuid) 
    {
        $loan = Loan::where('uuid', $uuid)->firstOrFail();
        return view('loan.show', compact('loan'));
    }

    public function testPdfDownload()
    {
        $user = User::latest()->first();
        
        $data = [
            'titulo' => 'Ahau Cash',
            'loan_amount' => 20000,
            'roi' => '20%',
            'payment_amount' => 24000,
            'user' => $user->toArray(),
        ];
    
        $pdf = Pdf::loadView('loan.pdf.test', $data);
    
        return $pdf->download(Str::uuid() . '.pdf');
    }
}
