<?php

namespace App\Services\Loan;

use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class CreatePDF
{
    public function execute(Loan $loan, Collection $installments)
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
