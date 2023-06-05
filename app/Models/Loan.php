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
        if ($this->status == 'SETTLED'
            || LoanInstallment::whereNull('paid_at')->where('loan_id', $this->id)->count() === 0) {
            return render_payment_status_label('SETTLED');
        }

        $currentInstallment = $this->installments()
            ->whereDate('start_date', '<=', now()->format('Y-m-d'))
            ->WhereDate('end_date', '>=', now()->format('Y-m-d'))
            ->first();

        if ($currentInstallment) {
            if ($currentInstallment->paid_at == null) {
                $previousInstallment = LoanInstallment::where('id', '<', $currentInstallment->id)
                    ->where('loan_id', '=', $this->id)
                    ->orderBy('id','desc')
                    ->first();

                if ($currentInstallment->end_date->diffInDays(now()) <= 10 && $previousInstallment->paid_at != null) {
                    return render_payment_status_label('CLOSE_TO_PAYMENT');
                } elseif ($previousInstallment && $previousInstallment->paid_at == null) {
                    return render_payment_status_label('PAST_DUE');
                } else {
                    return render_payment_status_label('CURRENT_LOAN');
                }
            } else {
                return render_payment_status_label('CURRENT_LOAN');
            }
        } else {
            $lastInstallment = LoanInstallment::where('loan_id', '=', $this->id)
                ->orderBy('id','desc')
                ->first();

            $firstInstallment = LoanInstallment::where('loan_id', '=', $this->id)
                ->orderBy('id','asc')
                ->first();

            if ($firstInstallment->start_date->format('m') > now()->format('m')) {
                return render_payment_status_label('PENDING_TO_START');
            }

            if ($firstInstallment->start_date->format('m') == now()->format('m')
                && $firstInstallment->start_date->format('d') > now()->format('d')
            ) {
                return render_payment_status_label('PENDING_TO_START');
            }

            if ($lastInstallment->paid_at == null) {
                return render_payment_status_label('PAST_DUE');
            }

            return render_payment_status_label('PENDING_TO_START');
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


