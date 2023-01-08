<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use NumberFormatter;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Loan extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'uuid',
        'user_id',
        'type',
        'amount',
        'duration_unit',
        'duration_period',
        'roi',
        'status',
        'installment_period',
        'created_by',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', ['PENDING', 'ACTIVE']);
    }

    public function formattedAmount(): string
    {
        $fmt = new NumberFormatter('es_MX', NumberFormatter::CURRENCY);
        return $fmt->formatCurrency($this->amount, "MXN");
    }

    public function amountToReturn(): string
    {
        $amount = $this->amount;
        $percentage = ($this->roi / 100) * $amount;
        $total = (float) $amount + $percentage;

        $fmt = new NumberFormatter('es_MX', NumberFormatter::CURRENCY);
        return $fmt->formatCurrency($total, "MXN");
        
    }

    public function loanPaymentStatus(): array
    {
        $paymentStatus = 'UNKNOWN';

        $firstInstallment = $this->installments()->first();
        // $daysFromStart = $firstInstallment->start_date->diffInDays(now());

        // revisar si el credito ya comenzo, usando la fecha del primer installment y comparando con la fecha actual
        $check = now()->between($firstInstallment->start_date, $firstInstallment->end_date);

        // el primer pagare esta en el rango, revisar si ya comenzo
        if ($check) {
            if ($firstInstallment->start_date < now()) {
                $paymentStatus = 'PENDING_TO_START';
            }
        } else {
            // si no esta en el rango porque ya comenzzo..
            $paymentStatus = 'CURRENT_LOAN';
            // buscar en que installment va para los siguientes calculos
            //$currentInstallment = $this->installments->nosequeonda

            // una vez ubicado, ver si esta al corriente (el installment previo fue pagado)
            //$paymentStatus = 'CURRENT_LOAN';

            // si esta al corriente pero esta en los ultimos 5 dias proximos a pagar
            //$paymentStatus = 'CLOSE_TO_PAYMENT';

            // si no esta al corriente marcar como vencido
            //$paymentStatus = 'PAST_DUE';
        }

        return match($paymentStatus) {
            'CURRENT_LOAN' => ['AL CORRIENTE', 'success'],
            'CLOSE_TO_PAYMENT' => ['LIMITE CERCANO', 'warning'],
            'PAST_DUE' => ['EN MORA', 'danger'],
            'PENDING_TO_START' => ['POR INICIAR', 'dark'],
            'UNKNOWN' => ['NO ESTATUS', 'dark'],
        };
    }

    public function installments(): HasMany
    {
        return $this->hasMany(LoanInstallment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}


