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

        $firstInstallment = $this->installments()->first();

        $nowMonth = now()->format('m');
        $firstInstallmentStartMonth = $firstInstallment->start_date->format('m');
        $firstInstallmentEndMonth = $firstInstallment->end_date->format('m');

        if ($nowMonth === $firstInstallmentStartMonth or $nowMonth === $firstInstallmentEndMonth) {
            if (now()->greaterThan($firstInstallment->start_date)) {
                return render_payment_status_label('CURRENT_LOAN');
            } else {
                return render_payment_status_label('PENDING_TO_START');
            }
        } else {
            $lastPaidInstallment = $this->installments()->whereNotNull('paid_at')->orderBy('start_date', 'DESC')->first();
            if ($lastPaidInstallment && $lastPaidInstallment->end_date->lessThan(now())) {
                return render_payment_status_label('PAST_DUE');
            } elseif ($lastPaidInstallment && $lastPaidInstallment->end_date->diff(now()) <= 10) {
                return render_payment_status_label('CLOSE_TO_PAYMENT');
            } else {
                return render_payment_status_label('PAST_DUE');
            }
        }

        return render_payment_status_label('UNKNOWN');
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


