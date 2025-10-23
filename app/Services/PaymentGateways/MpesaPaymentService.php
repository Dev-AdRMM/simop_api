<?php

namespace App\Services\PaymentGateways;

use Illuminate\Support\Str;
use Karson\MpesaPhpSdk\Mpesa;
use Illuminate\Support\Facades\Log;
use App\Models\Wallet;

class MpesaPaymentService
{
    private $mpesa;
    private $wallet;

    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
        $this->setupFromDatabase();
    }

    /**
     * Carrega as credenciais do banco de dados
     */
    private function setupFromDatabase(): void
    {
        $settings = $this->wallet->settings ?? [];

        $this->mpesa = new Mpesa();
        $this->mpesa->setApiKey($settings['api_key'] ?? '');
        $this->mpesa->setPublicKey($settings['public_key'] ?? '');
        $this->mpesa->setServiceProviderCode($settings['service_provider_code'] ?? '');
        $this->mpesa->setEnv($settings['env'] ?? 'sandbox');
    }

    /**
     * handle  payment request (C2B)
     */
    public function debitRequest(string $invoiceNumber, string $msisdn, float $amount): array
    {
        try {
            $reference = $this->generateReference();
            $transaction = $this->mpesa->c2b($invoiceNumber, $msisdn, $amount, $reference);

            Log::info('M-Pesa Debit Request', [
                'wallet' => $this->wallet->name,
                'invoice' => $invoiceNumber,
                'msisdn' => $msisdn,
                'amount' => $amount,
                'response' => $transaction,
            ]);

            if (!isset($transaction->response)) {
                return [
                    'status' => 'failed',
                    'message' => 'Serviço M-Pesa indisponível no momento.',
                ];
            }

            $response = $transaction->response;

            if (isset($response->output_ResponseCode)) {
                $status = $response->output_ResponseCode === 'INS-0' ? 'success' : 'failed';

                return [
                    'status' => $status,
                    'code' => $response->output_ResponseCode,
                    'message' => $response->output_ResponseDesc ?? 'Sem descrição.',
                    'transaction_id' => $response->output_TransactionID ?? null,
                    'reference' => $reference,
                ];
            }

            return [
                'status' => 'failed',
                'message' => 'Resposta inesperada da API M-Pesa.',
            ];
        } catch (\Throwable $e) {
            Log::error('Erro no MpesaPaymentService@debitRequest', [
                'wallet' => $this->wallet->name,
                'error' => $e->getMessage()
            ]);
            return [
                'status' => 'error',
                'message' => 'Erro interno no processamento do pagamento.',
            ];
        }
    }

    public function getTransactionStatus(string $transactionId): array
    {
        try {
            $response = $this->mpesa->status($transactionId);

            Log::info('M-Pesa Debit Status', [
                'wallet' => $this->wallet->name,
                'transaction_id' => $transactionId,
                'response' => $response,
            ]);

            return (array) $response;
        } catch (\Throwable $e) {
            Log::error('Erro no MpesaPaymentService@getTransactionStatus', [
                'wallet' => $this->wallet->name,
                'error' => $e->getMessage(),
            ]);
            return [
                'status' => 'error',
                'message' => 'Erro ao consultar o status da transação.',
            ];
        }
    }

    /**
     * Callback recebido da API da M-Pesa
     */
    public function callback(array $data): array
    {
        Log::info('M-Pesa Callback Recebido', $data);

        return [
            'status' => 'received',
            'data' => $data,
        ];
    }

    /**
     * Gera uma referência aleatória única
     */
    private function generateReference(): string
    {
        return Str::upper(Str::random(10));
    }
}
