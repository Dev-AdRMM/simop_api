<?php

namespace App\Services\PaymentGateways;

use Illuminate\Support\Str;
use Karson\MpesaPhpSdk\Mpesa;

class MpesaPaymentService
{
    private $mpesa;


    public function __construct()
    {
        $this->mpesa = new Mpesa();
        $this->mpesa->setApiKey(config('mpesa.api_key'));
        $this->mpesa->setPublicKey(config('mpesa.public_key'));
        $this->mpesa->setServiceProviderCode(config('mpesa.service_provider_code'));
        $this->mpesa->setEnv(config('mpesa.env'));// 'live' production environment
    }

    /**
     * handle  payment request

     * @param string $phone_number Phone number
     * @param float $amount Amount
     * @return string
     */

    public function purchase(string $invoice_number, string $phone_number, float $amount)
    {

        $transaction = $this->mpesa->c2b($invoice_number, $phone_number, $amount, $this->generateReference());

        if (!isset($transaction->response)) {
            return [
                'status' => 'failed',
                'message' => 'Serviço Indisponível',
            ];
        }


        if (isset($transaction->response->output_ResponseCode)) {
            if ($transaction->response->output_ResponseCode =='INS-0') {
                return [
                    'status' => 'success',
                    'code' => $transaction->response->output_ResponseCode,
                    'message' => 'Pagamento Feito com Sucesso',
                    'transaction_id' => $transaction->response->output_TransactionID,
                ];
            }

            return [
                'status' => 'failed',
                'code' => $transaction->response->output_ResponseCode,
                'message' => 'Pagamento Feito com Sucesso',
                'transaction_id' => $transaction->response->output_TransactionID,
            ];
        }

        if (isset($transaction->response->output_error)) {
            return [
                'status' => 'failed',
                'message' => 'Problemas com API',
            ];
        }
        return [
            'status' => 'failed',
            'message' => 'Erro no Pagamento',
        ];
    }

    private function generateReference()
    {
        return Str::upper(Str::random(10));
    }
}
