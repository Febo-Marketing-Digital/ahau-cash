<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanInstallment;
use Exception;
use Illuminate\Http\Request;

class LoanInstallmentController extends Controller
{
    public function show(int $id)
    {
        return view('loan-installment.show', [
            'item' => LoanInstallment::findorFail($id)
        ]);
    }

    public function storeNotePayment(Request $request, LoanInstallment $installment)
    {
        /* TODO:
            Debido a que el staff tambien podra subir comprobantes
            agregar un campo updated_by que guarde el id del user autenticado que hizo el update
        */
        $request->validate([
            'proof_of_payment' => 'file',
            'mark_as_paid' => "sometimes:accepted",
        ]);

        try {
            if ($request->hasFile('proof_of_payment')) {
                $installment
                    ->addMedia($request->proof_of_payment)
                    ->toMediaCollection('payments');
            }

            $installment->paid_at = now();
            $installment->save();

        } catch (Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        if (LoanInstallment::whereNull('paid_at')->where('loan_id', $installment->loan_id)->count() == 0) {
            $loan = Loan::find($installment->loan_id);
            //$loan->is_liquidated = 1;
            $loan->status = 'SETTLED';
            $loan->save();
        }

        // redirect to loan details
        return redirect(route('loan.show', $installment->loan->uuid));
    }
}
