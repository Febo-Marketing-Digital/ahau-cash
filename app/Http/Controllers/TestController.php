<?php

namespace App\Http\Controllers;

use App\Models\Loan;

class TestController extends Controller
{
    public function renderNote(Loan $loan)
    {
        return view('loan.pdf.contract_note', 
            [
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
            ]
        );
    }

    public function sendToCloud()
    {
        // Storage::disk('digitalocean')->putFile('staging', Storage::path('/../loans/2c2fcb68-85cf-47c0-9930-88bc3790d2fd-interest.pdf'), 'public');
    }
}