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
        // 🔹 Log no arquivo Laravel (sempre)
        Log::info("[$wallet] API Request", [
            'endpoint' => $endpoint,
            'method'   => $method,
            'headers'  => $headers,
            'payload'  => $payload,
            'response' => $response,
            'status'   => $status,
        ]);

        // 🔹 Se já existe no banco → atualiza
        if ($transactionId) {
            $transaction = Transaction::where('transaction_id', $transactionId)
                ->where('wallet', $wallet)
                ->first();

            if ($transaction) {
                $updateData = [
                    'status'            => $status,
                    'provider_response' => $response,
                ];

                if ($msisdn) {
                    $updateData['msisdn'] = $msisdn;
                }
                if ($amount) {
                    $updateData['amount'] = $amount;
                }

                $transaction->update($updateData);
                return $transaction;
            }
        }

        // 🔹 Só cria nova transação se tiver os dados obrigatórios
        if ($transactionId && $msisdn && $amount) {
            return Transaction::create([
                'wallet'            => $wallet,
                'transaction_id'    => $transactionId,
                'msisdn'            => $msisdn,
                'amount'            => $amount,
                'status'            => $status,
                'request_payload'   => is_array($payload) ? json_encode($payload) : $payload,
                'provider_response' => $response,
            ]);
        }

        // 🔹 Caso não tenha dados suficientes → só loga, não cria
        Log::warning("[$wallet] Tentativa de criar transação sem dados suficientes", [
            'transaction_id' => $transactionId,
            'msisdn'         => $msisdn,
            'amount'         => $amount,
            'status'         => $status,
            'response'       => $response,
        ]);

        return null;
    }
}
