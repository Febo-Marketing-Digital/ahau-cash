<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'street',
        'house_number',
        'locality',
        'province',
        'city',
        'state',
        'country',
        'postal_code',
    ];


    /**
     * Get the user's full address.
     */
    public function completeAddress(): string
    {
        return $this->street . ' ' . $this->house_number . ', ' 
            .  $this->locality .  ', ' .  $this->province .  ', '
            .  $this->city .  ', ' .  $this->state .  ', C.P.: ' .  $this->postal_code .  '.';
    }
}
