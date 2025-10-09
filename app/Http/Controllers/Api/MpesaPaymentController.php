<?php

namespace App\Http\Controllers\Api;

use App\Services\PaymentGateways\MpesaPaymentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\ApiLogsTransactions;

class MpesaPaymentController extends Controller
{
    use ApiLogsTransactions;

    protected $mpesa;

    public function __construct(MpesaPaymentService $mpesa)
    {
        $this->mpesa = $mpesa;
    }

    /**
     * 🔸 Efetua um débito (C2B)
     */
    public function debit_request(Request $request)
    {
        $request->validate([
            'msisdn' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'transaction_id' => 'required|string',
        ]);

        $response = $this->mpesa->debitRequest(
            $request->msisdn,
            $request->amount,
            $request->transaction_id
        );

        // 📌 Log da requisição usando Trait
        $this->logApi(
            'mpesa',
            '/api/v1/mpesa/debit_request',
            $request->method(),
            $request->headers->all(),
            $request->all(),
            $response,
            strtoupper($response['status'] ?? 'SENT'),
            $request->transaction_id,
            $request->msisdn,
            $request->amount
        );

        // if ($request->wantsJson()) {
        //     return response()->json($response, 200);
        // }

        return response($response, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * 🔸 Consulta status de uma transação
     */
    public function debit_status(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
        ]);

        $response = $this->mpesa->getTransactionStatus($request->transaction_id);

        Log::info("[mpesa] Consulta de status", [
            'transaction_id' => $request->transaction_id,
            'response' => $response
        ]);

        $status = $response['status'] ?? 'checked';

        // 📌 Log da consulta
        $this->logApi(
            'mpesa',
            '/api/v1/mpesa/debit_status',
            $request->method(),
            $request->headers->all(),
            $request->all(),
            $response,
            strtoupper($status),
            $request->transaction_id
        );

        if ($request->wantsJson()) {
            return response()->json([
                'transaction_id' => $request->transaction_id,
                'status' => $status,
                'response' => $response,
            ], 200);
        }

        $xmlResponse = $this->arrayToXml('response', $response);
        return response($xmlResponse, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * 🔸 Callback recebido da API M-Pesa
     */
    public function callback(Request $request)
    {
        $data = $request->all();
        Log::info("[mpesa] Callback recebido", $data);

        $transactionId = $data['input_TransactionID'] ?? null;
        $status = $data['output_ResponseDesc'] ?? 'RECEIVED';

        $this->logApi(
            'mpesa',
            '/api/v1/mpesa/callback',
            $request->method(),
            $request->headers->all(),
            $data,
            ['status' => 'ACKNOWLEDGED'],
            strtoupper($status),
            $transactionId
        );

        $response = [
            'status' => 'ACKNOWLEDGED',
            'transaction_id' => $transactionId
        ];

        if ($request->wantsJson()) {
            return response()->json($response, 200);
        }

        $xmlResponse = $this->arrayToXml('response', $response);
        return response($xmlResponse, 200)
            ->header('Content-Type', 'application/xml');
    }

}
