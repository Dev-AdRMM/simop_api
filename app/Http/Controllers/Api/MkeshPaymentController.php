<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Services\PaymentGateways\MkeshPaymentService;
use App\Traits\ApiLogsTransactions;
use App\Models\Transaction;

class MkeshPaymentController extends Controller
{
    use ApiLogsTransactions;

    protected $mkesh;

    public function __construct(MkeshPaymentService $mkesh)
    {
        $this->mkesh = $mkesh;
    }

    /**
     * Testa um débito
     */
    public function debit(Request $request)
    {
        $request->validate([
            'msisdn' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'transaction_id' => 'required|string',
        ]);

        $response = $this->mkesh->debitRequest(
            $request->msisdn,
            $request->amount,
            $request->transaction_id
        );

        // 🔹 Usa Trait
        $this->logApi(
            'mkesh',
            '/api/v1/mkesh/debit',
            $request->method(),
            $request->headers->all(),
            $request->all(),
            $response,
            'SENT',
            $request->transaction_id,
            $request->msisdn,
            $request->amount
        );

        return response($response, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Consulta status de uma transação
     */
    public function status(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
        ]);

        $response = $this->mkesh->getTransactionStatus($request->transaction_id);

        // Extrai o <status> do XML
        $xml = simplexml_load_string($response);
        $providerStatus = null;

        if ($xml && isset($xml->status)) {
            $providerStatus = strtolower((string) $xml->status); // "successful" ou "failed"
        } elseif ($xml && isset($xml['errorcode'])) {
            // Caso seja erro do provedor
            $providerStatus = 'not_found';
        }

         $transaction = Transaction::where('transaction_id', $request->transaction_id)->first();

        if ($transaction) {
            // Atualiza se existir
            $transaction->update([
                'provider_response' => $response,
                'status' => $providerStatus ?? 'checked',
            ]);
        } else {
            // Não insere nada se não existir no banco → só loga
            Log::warning("Consulta de transação inexistente no banco/local", [
                'transaction_id' => $request->transaction_id,
                'response' => $response
            ]);
        }

        // Salva log (sem duplicar)
        $this->logApi(
                'mkesh',
                '/api/v1/mkesh/status',
                $request->method(),
                $request->headers->all(),
                $request->all(),
                $response,
                $providerStatus ?? 'CHECKED',
                $request->transaction_id
        );


        return response($response, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Endpoint de Callback (recebe XML)
     */
    public function callback(Request $request)
    {
        $xmlContent = $request->getContent();
        Log::info("Callback Mkesh recebido", ['xml' => $xmlContent]);

        // Parse XML
        $xml = simplexml_load_string($xmlContent);
        $transactionId = (string) $xml->transactionid ?? null;
        $externalTransactionId = (string) $xml->externaltransactionid ?? null;
        $status = (string) $xml->status ?? 'RECEIVED';

        // Atualiza transação existente
        if ($externalTransactionId) {
            Transaction::where('transaction_id', $externalTransactionId)
                ->update([
                    'status' => strtolower($status),
                    'provider_response' => $xmlContent,
                ]);
        }

        // Salva log (com IDs corretos)
        $this->logApi(
            'mkesh',
            '/api/v1/mkesh/callback',
            $request->method(),
            $request->headers->all(),
            $xmlContent,
            '<response>ACKNOWLEDGED</response>',
            $status,
            $externalTransactionId
        );

        $response = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <response>
            <status>ACKNOWLEDGED</status>
            </response>
            XML;

        return response($response, 200)
            ->header('Content-Type', 'application/xml');
    }
}
