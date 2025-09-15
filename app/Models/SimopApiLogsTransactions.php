<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimopApiLogsTransactions extends Model
{
    protected $table = 'simop_api_logs_transactions';

    protected $fillable = [
        'endpoint',
        'method',
        'headers',
        'payload',
        'response'
    ];
}
