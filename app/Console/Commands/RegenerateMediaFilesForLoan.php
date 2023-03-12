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
    protected $signature = 'ahau-cash:regenerate-media-files {loan_uuid}';

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
        $loan = Loan::where('uuid', '=', $this->argument('loan_uuid'))->first();

        if ($loan) {
            $mediaFiles = $loan->getMedia('notes');
            foreach ($mediaFiles as $mediaFile) {
                $mediaFile->delete();
            }

            $installments = LoanInstallment::where('loan_id', $loan->id)->get();

            foreach ($installments as $installment) {
                $mediaFiles = $installment->getMedia('notes');
                $mediaFiles[0]->delete();
            }

            $createPDF->execute($loan, $installments);
        } else {
            $this->info('no existe un prestamo con el UUID ' . $this->argument('loan_uuid'));
        }

        return Command::SUCCESS;
    }
}
