<?php

namespace App\Http\Controllers\Api;

use App\Services\PaymentGateways\MpesaPaymentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MpesaPaymentController extends Controller
{
    private $mpesaService;

    public function __construct(MpesaPaymentService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    public function processPayment(Request $request)
    {

        // $this->logApi(
        //     'mpesa',
        //     '/api/v1/mpesa/debit',
        //     $request->method(),
        //     $request->headers->all(),
        //     $request->all(),
        //     $response,
        //     'SENT',
        //     $request->transaction_id,
        //     $request->msisdn,
        //     $request->amount
        // );


        try {
            $request->validate([
                'phone_number' => 'required|regex:/^258\d{9}$/',
                'amount' => 'required|numeric|min:1',
            ]);

            $invoice_number = 3333;
            $phone_number = $request->input('phone_number');
            $amount = $request->input('amount');

            $result = $this->mpesaService->purchase($invoice_number, $phone_number, $amount);

            if ($result['status'] === 'success') {
                return response()->json([
                    'status' => 'success',
                    'message' => $result['message'],
                    'transaction_id' => $result['transaction_id'],
                ]);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => $result['message'],
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}



