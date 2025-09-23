<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

trait ApiLogsTransactions
{
    /**
     * Registra requisições/respostas de APIs e atualiza/cria transações
     */
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

        // 🔹 Prepara provider_response
        $providerResponse = $this->extractErrorCode($response);

        // 🔹 Atualiza transação existente, se existir
        if ($transactionId) {
            $transaction = Transaction::where('transaction_id', $transactionId)
                ->where('wallet', $wallet)
                ->first();

            if ($transaction) {
                $updateData = [
                    'status'            => $status,
                    'provider_response' => $providerResponse,
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

        // 🔹 Cria nova transação se houver dados completos
        if ($transactionId && $msisdn && $amount) {
            return Transaction::create([
                'wallet'            => $wallet,
                'transaction_id'    => $transactionId,
                'msisdn'            => $msisdn,
                'amount'            => $amount,
                'status'            => $status,
                'provider_response' => $providerResponse,
            ]);
        }

        // 🔹 Caso não tenha dados suficientes → só loga, não cria
        Log::warning("[$wallet] Tentativa de criar transação sem dados suficientes", [
            'transaction_id' => $transactionId,
            'msisdn'         => $msisdn,
            'amount'         => $amount,
            'status'         => $status,
            'response'       => $providerResponse,
        ]);

        return null;
    }

    /**
     * Extrai o atributo errorcode de respostas XML do provedor
     */
    protected function extractErrorCode($response)
    {
        if (is_string($response) && str_starts_with(trim($response), '<?xml')) {
            $xml = @simplexml_load_string($response);
            if ($xml && isset($xml['errorcode'])) {
                return (string) $xml['errorcode'];
            }
        }
        return $response; // mantém o conteúdo original se não houver errorcode
    }
}
