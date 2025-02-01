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
    public function index(Request $request)
    {
        $loansQuery = Loan::query();
        $loansQuery->with(['user', 'installments']);

        if ($name = $request->get('name')) {
            $loansQuery->where(function ($query) use ($name) {
                $query->whereRelation('user', 'name', 'like', '%' . $name . '%')
                    ->orWhereRelation('user', 'lastname', 'like', '%' . $name . '%');
            });
        }

        if ($from = $request->get('from_start_date')) {
            $to = $request->get('to_start_date') ?? now();
            $loansQuery->whereBetween('start_date', [$from, $to]);
        }

        //        if (auth()->user()->type == 'staff') {
        //            $loans = $loansQuery->where('created_by', auth()->user()->id)->latest()->paginate(25);
        //        } else {
        //            $loans = $loansQuery->orderBy('start_date', 'desc')->paginate(25);
        //        }
        $loans = $loansQuery->orderBy('start_date', 'desc')->paginate(25);

        return view('loan.index', compact('loans'));
    }

    public function create(Request $request)
    {
        if ($request->has('client_id')) {
            $clients = User::where('id', $request->get('client_id'))->get();
        } else {
            $allCLients = User::with('address', 'bankDetails')
                ->where('type', 'client')
                ->orderBy('name')
                ->get();

            $clients = $allCLients->reject(function ($client) {
                return is_null($client->address) or is_null($client->bankDetails);
            });
        }

        if ($clients->isEmpty()) {
            return redirect()->route('client.create')->with('message', 'warning|Debe registrar al menos un cliente');
        }

        return view('loan.create', compact('clients'));
    }

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

            $loanStatus = auth()->user()->can('approve loan') ? 'APPROVED' : 'PENDING';

            // se crea el prestamo en status pendiente de activacion
            $loan = Loan::create([
                'uuid' => Str::uuid(),
                'user_id' => $request->client,
                //'group_loan_id' => $request->group_loan_id, // make user_id nullable and create a model GroupLoan which has many users and belongs to one loan
                'type' => $request->loan_type,
                'amount' => $request->amount,
                'duration_unit' => $request->installment_quantity, // 12
                'duration_period' => 'MONTH', // $request->installment_period: TODO cambiar este campo o crear uno nuevo
                'roi' => $request->loan_roi,
                'installment_period' => $request->installment_period,
                'status' => $loanStatus,
                'created_by' => auth()->user()->id,
                'start_date' => $request->start_date ?? now(),
            ]);

            // se crea la tabla de amortizacion
            $amount = $request->amount;
            $percentage = ($request->loan_roi / 100) * $amount;
            $finalBalance = $amount + $percentage;
            $installmentAmount = $finalBalance / $request->installment_quantity;

            //$daysBy = $request->installment_period == 'MONTHLY' ? 30 : 15;
            //$startDate = \Carbon\Carbon::parse($request->start_date);
            //$endDate = $startDate->copy()->addDays($daysBy);

            if ($loan->installment_period == 'MONTHLY') {
                $installments = $this->generateMonthlyPayments($loan, $finalBalance, $installmentAmount);
                //$installments = $this->generateInstallments($loan, $finalBalance, $installmentAmount, $request->installment_quantity);
            } else {
                $installments = $this->generateBiweeklyPayments($loan, $finalBalance, $installmentAmount);
            }

            // se crea el PDF
            $this->generatePdfs($loan, $installments);

            DB::commit();

            $message = 'Prestamo creado con exito';
            $cssClass = "bg-teal-600";
        } catch (Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();
            $cssClass = "bg-red-600";
        }

        return redirect(route('loan.index'))->with('message', $message)->with('class', $cssClass);
    }

    private function generateMonthlyPayments(Loan $loan, $finalBalance, $installmentAmount)
    {
        $paymentsNums = $loan->duration_unit;
        $currentBalance = $finalBalance;

        $installments = [];

        for ($pi = 1; $pi <= $paymentsNums; $pi++) {
            // initial date is end of the month
            if ($loan->start_date->day == $loan->start_date->copy()->endOfMonth()->day) {
                if ($pi == 1) {
                    $startDate = $loan->start_date;
                } else {
                    $startDate = $endDate->copy(); // this is necessary?
                }
                $nextMonth = $startDate->copy()->addDays(1);
                $endDate = $nextMonth->endOfMonth();
            } else {
                if ($pi == 1) {
                    $startDate = $loan->start_date;
                } else {
                    $startDate = $endDate->copy(); // this is necessary?
                }
                $endDate = $startDate->copy()->addMonths(1);
            }

            $currentBalance = $currentBalance - $installmentAmount;

            $installment = LoanInstallment::create([
                'loan_id' => $loan->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'amount' => $installmentAmount,
                'balance' => $currentBalance,
            ]);

            $installments[] = $installment;
        }

        return $installments;
    }

    private function generateBiweeklyPayments(Loan $loan, $finalBalance, $installmentAmount)
    {
        //throw new Exception('Installment period not implemented yet!');
        $paymentsNums = $loan->duration_unit;
        $currentBalance = $finalBalance;

        $installments = [];

        for ($pi = 1; $pi <= $paymentsNums; $pi++) {
            // initial date is end of the month
            if ($loan->start_date->day == $loan->start_date->copy()->addDays(15)->day) {
                if ($pi == 1) {
                    $startDate = $loan->start_date;
                } else {
                    $startDate = $endDate->copy();
                }
                $nextMonth = $startDate->copy()->addDays(1);
                $endDate = $nextMonth->addDays(15);
            } else {
                if ($pi == 1) {
                    $startDate = $loan->start_date;
                } else {
                    $startDate = $endDate->copy();
                }
                $endDate = $startDate->copy()->addDays(15);
            }

            $currentBalance = $currentBalance - $installmentAmount;

            $installment = LoanInstallment::create([
                'loan_id' => $loan->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'amount' => $installmentAmount,
                'balance' => $currentBalance,
            ]);

            $installments[] = $installment;
        }

        return $installments;
    }

    private function generateInstallments(Loan $loan, $finalBalance, $installmentAmount, $installmentQuantity): array
    {
        $startDate = $loan->start_date;
        $installments = [];
        $installmentStartIsEndOfMonth = false;

        // Calculate end date based on the month
        if ($loan->installment_period == 'MONTHLY') {
            if ($startDate->day == $startDate->copy()->endOfMonth()->day) {
                // Loan starts at the end of month
                $installmentStartIsEndOfMonth = true;
                $nextMonth = $startDate->copy()->addDays(1);
                $endDate = $nextMonth->endOfMonth();
            } else {
                $month = $startDate->month;
                $daysBy = $startDate->month($month)->daysInMonth - 1;
                $endDate = $startDate->copy()->addDays($daysBy);
                //$endDate = $startDate->copy()->addMonths(1)->subDays(1);
            }
        } else {
            throw new Exception('Installment period not implemented yet!');
        }

        $installment = null;

        for ($i = 1; $i <= $installmentQuantity; $i++) {

            if ($installmentAmount > $finalBalance) {
                $installmentAmount = $finalBalance;
            }

            // Calculate dates
            if ($i > 1) {
                if ($installmentStartIsEndOfMonth) {
                    // Si un prestamo comienza en fin de mes, todos los prestamos deben ser establecidos
                    // a fin de mes, sin importar el calculo de dias, ej:
                    // 31/10/2023 -> 30/11/2023
                    // 30/11/2023 -> 31/12/2023
                    // 31/12/2023 -> 31/01/2024
                    // 31/01/2021 -> 29/02/2024 .. etc
                    $startDate = $installment->end_date->copy();
                    $nextMonth = $startDate->copy()->addDays(1);
                    $endDate = $nextMonth->endOfMonth();
                } else {
                    $startDate = $endDate->copy()->addDays(1);
                    $month = $endDate->month;
                    $daysBy = $endDate->month($month)->daysInMonth - 1;
                    $endDate = $startDate->copy()->addDays($daysBy);
                }
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

        return $installments;
    }

    public function show(string $uuid)
    {
        setlocale(LC_TIME, 'es_ES');

        $loan = Loan::where('uuid', $uuid)->firstOrFail();

        $table = "";
        // $c = (int) $loan->amount; // Capital inicial
        // $n = 4; // Numero de CUOTAS
        // $i = ($loan->roi)/ 100; // Tasa de Interes

        // //$a = $c*(($i)/ (  1 - pow((1+$i) , ($n)*-1) )); // Calcular Amortizacion
        // $interest_amount = ($loan->roi / 100) * $loan->amount;
        // //dump($interest_amount);
        // $a = ($loan->amount + $interest_amount) / $n;

        // $a = number_format($a,2,".",""); // Formatear numero a 2 decimales
        // $saldo_inicial = $c;
        // $saldo_inicial = number_format((float)$saldo_inicial,2,".",""); // Formatear numero a 2 decimales

        // $table =  "<h2>TABLA DE AMORTIZACION</h2>";
        // $table .= "<table class='table table-striped'>";
        // $table .= "<td>No.</td>";
        // $table .= "<td>Saldo Inicial</td>";
        // $table .= "<td>Amortizacion</td>";
        // $table .= "<td>Interes</td>";
        // $table .= "<td>Abono Capital</td>";
        // $table .= "<td>Saldo Final</td>";

        // for($ix=1; $ix<=$n; $ix++){
        //     $interes = $saldo_inicial*$i; // se calcula el interes para este ciclo
        //     $interes = number_format((float)$interes,2,".",""); // Formatear numero a 2 decimales

        //     $abono_capital = $a - $interes; // el abono a capital es la amortizacion menos el interes del ciclo
        //     $abono_capital = number_format((float)$abono_capital,2,".",""); // Formatear numero a 2 decimales

        //     $saldo_final = $saldo_inicial - $abono_capital;
        //     $saldo_final = number_format((float)$saldo_final,2,".",""); // Formatear numero a 2 decimales

        //     $table .= "<tr>";
        //     $table .= "<td>".$ix."</td>";
        //     $table .= "<td>".$saldo_inicial."</td>";
        //     $table .= "<td>".$a."</td>";
        //     $table .= "<td>".$interes."</td>";
        //     $table .= "<td>".$abono_capital."</td>";
        //     $table .= "<td>".$saldo_final."</td>";

        //     $table .= "<tr>";

        //     $saldo_inicial = $saldo_final;
        //     $saldo_inicial = number_format((float)$saldo_inicial,2,".",""); // Formatear numero a 2 decimales
        // }

        // $table .= "</table>";

        return view('loan.show', compact('loan', 'table'));
    }

    public function destroy(Loan $loan)
    {
        if (auth()->user()->type == 'admin') {
            // valida si el prestamo no esta en proceso???

            $installments = LoanInstallment::where('loan_id', $loan->id)->get();
            foreach ($installments as $installment) {
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
            // $loan->is_liquidated = 1;
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
        $pdf->save(storage_path('loans/' . $loan->uuid . "-contract.pdf"));

        $loan
            ->addMedia(storage_path('loans/' . $loan->uuid . "-contract.pdf"))
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
            'start_date' => $loan->start_date,
            'loan_duration' => $loan->duration_unit,
        ];

        $pdf = Pdf::loadView('loan.pdf.loan_note', $data);
        $pdf->save(storage_path('loans/' . $loan->uuid . "-total.pdf"));

        $loan
            ->addMedia(storage_path('loans/' . $loan->uuid . "-total.pdf"))
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
            'start_date' => $loan->start_date,
            'loan_duration' => $loan->duration_unit,
        ];

        $pdf = Pdf::loadView('loan.pdf.interest_note', $data);
        $pdf->save(storage_path('loans/' . $loan->uuid . "-interest.pdf"));

        $loan
            ->addMedia(storage_path('loans/' . $loan->uuid . "-interest.pdf"))
            ->withCustomProperties(['note_type' => 'total_interests_note', 'note_name' => 'Pagaré Intereses'])
            ->toMediaCollection('notes');

        // 4 .- se generan todos los pagares de los pagos mensuales o quincenales
        foreach ($installments as $key => $installment) {
            $data = [
                'loan_installment_amount' => $installment->amount,
                'roi' => $loan->roi . '%',
                'fullname' => $loan->user->fullname(),
                'user' => $loan->user->toArray(),
                'address' => $loan->user->address,
                'end_date' => $installment->end_date,
                'start_date' => $loan->start_date,
            ];

            $pdfInstallment = Pdf::loadView('loan.pdf.installment_note', $data);
            $pdfInstallment->save(storage_path('loans/' . $loan->uuid . "-" . $key . "-.pdf"));

            $installment
                ->addMedia(storage_path('loans/' . $loan->uuid . "-" . $key . "-.pdf"))
                ->withCustomProperties(['note_type' => 'installment_note', 'note_name' => 'Pagaré ' . $key])
                ->toMediaCollection('notes');
        }
    }
}
