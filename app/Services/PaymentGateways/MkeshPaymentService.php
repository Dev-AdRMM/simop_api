<?php

namespace App\Services\PaymentGateways;

use Illuminate\Support\Facades\Log;

class MkeshPaymentService
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $endpoints;

    public function __construct()
    {
        $this->baseUrl = config('mkesh.base_url');
        $this->username = config('mkesh.username');
        $this->password = config('mkesh.password');
        $this->endpoints = config('mkesh.endpoints');
    }

    /**
     * Envia requisição XML para o endpoint especificado
     */
    private function sendXmlRequest(string $endpoint, string $xmlPayload): string
    {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: text/xml",
            ],
            CURLOPT_USERPWD => "{$this->username}:{$this->password}",
            CURLOPT_POSTFIELDS => $xmlPayload,
            CURLOPT_SSL_VERIFYPEER => false, // ⚠️ desabilitar só para testes
            CURLOPT_TIMEOUT => 60,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::error("Erro cURL: " . $error);
            throw new \Exception("Erro ao comunicar com mKesh: $error");
        }

        Log::info("Requisição para {$url}", ['payload' => $xmlPayload, 'resposta' => $response]);

        return $response;
    }

    /**
     * Inicia um débito
     */
    public function debitRequest(string $msisdn, float $amount, string $transactionId): string
    {
        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <ns0:debitrequest xmlns:ns0="http://www.ericsson.com/em/emm/financial/v1_1">
            <fromfri>FRI:{$msisdn}/MSISDN</fromfri>
            <tofri>FRI:pagamKesh/USER</tofri>
            <amount>
                <amount>{$amount}</amount>
                <currency>MZN</currency>
            </amount>
            <externaltransactionid>{$transactionId}</externaltransactionid>
            <referenceid>{$transactionId}</referenceid>
            </ns0:debitrequest>
            XML;

        return $this->sendXmlRequest($this->endpoints['debit'], $xml);
    }

    /**
     * Consulta o status de uma transação
     */
    public function getTransactionStatus(string $transactionId): string
    {
        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <ns0:gettransactionstatusrequest xmlns:ns0="http://www.ericsson.com/em/emm/financial/v1_3">
            <referenceid>{$transactionId}</referenceid>
            </ns0:gettransactionstatusrequest>
            XML;

        return $this->sendXmlRequest($this->endpoints['status'], $xml);
    }
}
