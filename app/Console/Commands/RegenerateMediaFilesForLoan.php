<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Services\Loan\CreatePDF;
use Illuminate\Console\Command;

class RegenerateMediaFilesForLoan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahau-cash:regenerate-media-files {uuid?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerar archivos PDF para un prestamo existente';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(CreatePDF $createPDF)
    {
        $query = Loan::query();

        // process one or all of loans
        if ($uuid = $this->argument('uuid')) {
            $query->where('uuid', '=', $uuid);
        }

        // if ($dateInput = $this->argument('dates')) {
        //     $dates = explode(',', $dateInput);
        //     dump($dates);
        // }

        $loans = $query->get();

        $total = 0;

        if ($loans) {
            foreach ($loans as $loan) {
                $mediaFiles = $loan->getMedia('notes');
                foreach ($mediaFiles as $mediaFile) {
                    $mediaFile->delete();
                }

                $installments = LoanInstallment::where('loan_id', $loan->id)->get();

                foreach ($installments as $installment) {
                    $mediaFiles = $installment->getMedia('notes');

                    if ($mediaFiles->count() > 0) {
                        foreach ($mediaFiles as $file) {
                            $file->delete();
                        }
                    }
                }

                $createPDF->execute($loan, $installments);

                $total++;
            }
        } else {
            $this->warn('No loans available for processing.');
        }

        $this->info('Process conclude. Loans: ' . $total);

        return Command::SUCCESS;
    }
}
