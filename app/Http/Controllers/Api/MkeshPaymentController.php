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

        Transaction::where('transaction_id', $request->transaction_id)
            ->update(['provider_response' => $response]);

        // salvar log
        $this->logApi(
            'mkesh',
            '/api/v1/mkesh/status',
            $request->method(),
            $request->headers->all(),
            $request->all(),
            $response,
            'CHECKED',
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

        $this->logApi(
            'mkesh',
            '/api/v1/mkesh/callback',
            $request->method(),
            $request->headers->all(),
            $xmlContent,
            '<response>ACKNOWLEDGED</response>',
            'RECEIVED'
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
