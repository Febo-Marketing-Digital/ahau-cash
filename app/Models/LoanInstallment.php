<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NumberFormatter;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class LoanInstallment extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'loan_id',
        'start_date',
        'end_date',
        'amount',
        'balance',
        'paid_at,'
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function formattedAmount(): string
    {
        $fmt = new NumberFormatter('es_MX', NumberFormatter::CURRENCY);
        return $fmt->formatCurrency($this->amount, "MXN");
    }

    public function formattedBalance(): string
    {
        $fmt = new NumberFormatter('es_MX', NumberFormatter::CURRENCY);
        return $fmt->formatCurrency($this->balance, "MXN");
    }

    public function getMediaLink(): string
    {
        if ($this->hasMedia('notes')) {
            $mediaItems = $this->getMedia('notes');
            $linkUrl = "";
            if (!empty($mediaItems[0])) {
                $linkUrl = app()->environment('local') ? $mediaItems[0]->getFullUrl() : $mediaItems[0]->getTemporaryUrl(Carbon::now()->addMinutes(10));
            }
            return '<a class="btn btn-dark" target="_blank" title="descargar PDF" href="' . $linkUrl . '" download><i class="bi bi-arrow-down-square"></i></a>';
        } else {
            return 'N/A';
        }
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
