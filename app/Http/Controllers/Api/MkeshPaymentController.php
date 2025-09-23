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

        // 🔹 Usa Trait para log e salvar transação
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

        $xml = @simplexml_load_string($response);
        $providerStatus = null;

        if ($xml && isset($xml->status)) {
            $providerStatus = strtolower((string) $xml->status);
        } elseif ($xml && isset($xml['errorcode'])) {
            $errorCode = (string) $xml['errorcode'];

            Log::error("[mkesh] Erro na consulta de transação", [
                'transaction_id' => $request->transaction_id,
                'error_code'     => $errorCode,
                'response'       => $response,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'transaction_id' => $request->transaction_id,
                    'status'         => 'error',
                    'error_code'     => $errorCode,
                    'message'        => 'Transação não encontrada no provedor'
                ], 404);
            }

            return response($response, 404)
                ->header('Content-Type', 'application/xml');
        }

        // 🔹 Usa trait para atualizar ou logar transação
        $this->logApi(
            'mkesh',
            '/api/v1/mkesh/status',
            $request->method(),
            $request->headers->all(),
            $request->all(),
            $response,
            strtoupper($providerStatus ?? 'CHECKED'),
            $request->transaction_id
        );

        if ($request->wantsJson()) {
            return response()->json([
                'transaction_id' => $request->transaction_id,
                'status'         => $providerStatus ?? 'checked',
                'response'       => $response,
            ], 200);
        }

        return response($response, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Callback do provedor (recebe XML)
     */
    public function callback(Request $request)
    {
        $xmlContent = $request->getContent();
        Log::info("Callback Mkesh recebido", ['xml' => $xmlContent]);

        $xml = @simplexml_load_string($xmlContent);
        $transactionId = (string) ($xml->transactionid ?? null);
        $externalTransactionId = (string) ($xml->externaltransactionid ?? null);
        $status = (string) ($xml->status ?? 'RECEIVED');

        // 🔹 Usa trait para atualizar ou criar transação
        $this->logApi(
            'mkesh',
            '/api/v1/mkesh/callback',
            $request->method(),
            $request->headers->all(),
            $xmlContent,
            '<response>ACKNOWLEDGED</response>',
            strtoupper($status),
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
