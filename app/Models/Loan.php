<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

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

    public function installments(): HasMany
    {
        return $this->hasMany(LoanInstallment::class);
    }
}
