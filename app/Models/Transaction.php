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
        'request_payload',
        'provider_response',
    ];

    // 🔹 Garante que as datas saem formatadas no JSON
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
