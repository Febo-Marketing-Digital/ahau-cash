<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'proof_of_payment' => ['required', 'file'],
        ]);

        try {

            $installment
                ->addMedia($request->proof_of_payment)
                ->toMediaCollection('payments');

            $installment->paid_at = now();
            $installment->save();
            

        } catch (Exception $exception) {
            dd($exception->getMessage());

            return redirect()->back();
        }

        // redirect to documentation module
        return redirect(route('loan.index'));
    }
}