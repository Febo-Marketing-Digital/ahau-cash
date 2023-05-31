<?php

namespace App\Models;

use Carbon\Carbon;
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
        'start_date',
    ];

    public $dates = [
        'start_date',
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

    public function getMediaLink(): string
    {
        if ($this->hasMedia('notes')) {
            $mediaItems = $this->getMedia('notes');
            $renderText = '';
            foreach($mediaItems as $mediaItem) {
                $linkText = $mediaItem->getCustomProperty('note_name') ?? $mediaItem->name;
                $linkUrl = app()->environment('local') ? '' : $mediaItem->getTemporaryUrl(Carbon::now()->addMinutes(10));
                $renderText .= '<p>DESCARGAR PDF: <a target="_blank" href=' . $linkUrl . ' download>' . $linkText . '</a></p>';
            }

            return $renderText;
        }

        return '';
    }

    public function loanPaymentStatus(): array
    {
        if ($this->status == 'SETTLED') {
            return render_payment_status_label('SETTLED');
        }

        if (LoanInstallment::whereNull('paid_at')->where('loan_id', $this->id)->count() === 0) {
            return render_payment_status_label('SETTLED');
        }

        $firstInstallment = $this->installments()->first();

        $nowMonth = now()->format('m');
        $firstInstallmentStartMonth = $firstInstallment->start_date->format('m');
        //$firstInstallmentEndMonth = $firstInstallment->end_date->format('m');

        $lastPaidInstallment = LoanInstallment::whereNotNull('paid_at')->where('loan_id', $this->id)->latest()->first();

        if ($lastPaidInstallment && is_null($lastPaidInstallment->paid_at) && $lastPaidInstallment->end_date->lessThan(now())) {
            return render_payment_status_label('PAST_DUE');
        } elseif ($lastPaidInstallment && ($lastPaidInstallment->end_date->diffInDays(now()) <= 10)) {
            return render_payment_status_label('CLOSE_TO_PAYMENT');
        }

        // Si la fecha de inicio es mayor a hoy el credito esta por inciar
        if ($this->start_date > now()) {
            return render_payment_status_label('PENDING_TO_START');
        }
        // Si el mes y año del primer pago coincide con el mes y año de la fecha actual
        if ($firstInstallmentStartMonth === $nowMonth && $firstInstallment->end_date->format('Y') === now()->format('Y')) {
            // Si la fecha final esta a 10 de la fecha actual
            if ($firstInstallment->end_date->diffInDays(now()) <= 10) {
                return render_payment_status_label('CLOSE_TO_PAYMENT');
            } else {
                // el credito esta al corriente
                return render_payment_status_label('CURRENT_LOAN');
            }
        }

        return render_payment_status_label('PAST_DUE');
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


