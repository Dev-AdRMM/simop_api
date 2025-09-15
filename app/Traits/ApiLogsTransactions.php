<?php

namespace App\Traits;

use App\Models\SimopApiLogsTransactions;

trait ApiLogsTransactions
{
    public function logApi($endpoint, $method, $headers, $payload, $response = null)
    {
        return SimopApiLogsTransactions::create([
            'endpoint' => $endpoint,
            'method'   => $method,
            'headers'  => json_encode($headers),
            'payload'  => $payload,
            'response' => $response,
        ]);
    }
}
