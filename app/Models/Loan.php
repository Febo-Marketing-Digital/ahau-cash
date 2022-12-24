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
        // checar si el prestamo esta al corriente (regresa CURRENT / green)
        // si esta proximo a pagar la siguiente cuota regresa NEXT / yellow
        // si tiene cuotas vencidas regresa PAST_DUE / red
        $paymentStatus = 'CURRENT';

        return match($paymentStatus) {
            'CURRENT' => ['AL CORRIENTE', 'success'],
            'NEXT' => ['LIMITE CERCANO', 'warning'],
            'PAST_DUE' => ['EN MORA', 'danger'],
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
}
