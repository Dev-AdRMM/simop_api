<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'wallet',
        'transaction_id',
        'msisdn',
        'amount',
        'status',
        'provider_response',
    ];
}
