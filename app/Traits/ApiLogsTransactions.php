<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

trait ApiLogsTransactions
{
    public function logApi(
            string $wallet,
            string $endpoint,
            string $method,
            array $headers,
            $payload,
            $response = null,
            string $status = 'PENDING',
            ?string $transactionId = null,
            ?string $msisdn = null,
            ?float $amount = null
    ) {
        // 🔹 Log no arquivo Laravel
        Log::info("[$wallet] API Request", [
            'endpoint' => $endpoint,
            'method'   => $method,
            'headers'  => $headers,
            // 'headers'  => json_encode($headers),
            'payload'  => $payload,
            'response' => $response,
            'status'   => $status,
        ]);

        // 🔹 Salva no banco (transactions)
        return Transaction::create([
            'wallet'           => $wallet,
            'transaction_id'    => $transactionId,
            'msisdn'            => $msisdn,
            'amount'            => $amount,
            'status'            => $status,
            'request_payload'   => is_array($payload) ? json_encode($payload) : $payload,
            'provider_response' => $response,
        ]);

    }
}
