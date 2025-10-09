<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

trait ApiLogsTransactions
{
    /**
     * Registra requisições/respostas de APIs e atualiza/cria transações.
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

        /**
         * 🔸 Extrai um resumo do response do provedor
         *  - Pode vir em XML (caso do Mkesh)
         *  - Ou em array (caso do Mpesa)
         */
        if ($response) {
            $providerResponse = $this->extractProviderStatus($response);

            if ($providerResponse === $response) {
                $providerResponse = $this->extractErrorCode($response);
            }

            // ✅ Garante que será sempre uma string antes de salvar
            if (is_array($providerResponse)) {
                $providerResponse = $providerResponse['code']
                    ?? $providerResponse['status']
                    ?? json_encode($providerResponse);
            }
        } else {
            $providerResponse = null;
        }

        /**
         * 🔸 Se já existe a transação, atualiza
         */
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

        /**
         * 🔸 Cria nova transação se tiver dados suficientes
         */
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

        /**
         * 🔸 Caso falte informação, apenas registra o log
         */
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
     * Extrai apenas o status de uma resposta XML
     */
    protected function extractProviderStatus($response)
    {
        if (is_string($response) && str_starts_with(trim($response), '<?xml')) {
            $xml = @simplexml_load_string($response);
            if ($xml && isset($xml->status)) {
                return strtoupper((string) $xml->status);
            }
        }

        // Caso o response seja array (Mpesa)
        if (is_array($response)) {
            return $response['status'] ?? null;
        }

        return $response; // mantém original se não tiver status
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
        return $response;
    }
}
