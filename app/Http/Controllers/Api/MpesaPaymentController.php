<?php

namespace App\Http\Controllers\Api;

use App\Services\PaymentGateways\MpesaPaymentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\ApiLogsTransactions;
use Illuminate\Support\Facades\Log;
use App\Models\Wallet;


class MpesaPaymentController extends Controller
{
    use ApiLogsTransactions;

    public function debit_request(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|uuid',
            'msisdn' => 'required|regex:/^258\d{9}$/',
            'amount' => 'required|numeric|min:1',
            'invoiceNumber' => 'required|string',
        ]);

        $wallet = Wallet::findOrFail($request->wallet_id);

        $mpesa = new MpesaPaymentService($wallet);

        $response = $mpesa->debitRequest(
            $request->invoiceNumber,
            $request->msisdn,
            $request->amount
        );

        $this->logApi(
            strtoupper($wallet->provider . ' - ' . $wallet->name),
            '/api/v1/mpesa/debit_request',
            $request->method(),
            $request->headers->all(),
            $request->all(),
            $response,
            strtoupper($response['status'] ?? 'SENT'),
            $request->invoiceNumber,
            $request->msisdn,
            $request->amount
        );

        return response()->json($response)
         ->header('Content-Type', 'application/xml');
    }

     # Consulta status de uma transação
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

        # Log da consulta
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

        return response($response, 200)
            ->header('Content-Type', 'application/xml');
    }

     # Callback recebido da API M-Pesa
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

        return response($response, 200)
            ->header('Content-Type', 'application/xml');
    }
}
