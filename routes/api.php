<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MkeshPaymentController;
use App\Http\Controllers\Api\EmolaPaymentController;
use App\Http\Controllers\Api\MpesaPaymentController;

#Api Payment

Route::prefix('v1')->group(function () {

    Route::middleware('verify.simop.apikey')->group(function () {
        Route::post('/mkesh/debit_request', [MkeshPaymentController::class, 'debit_request']);
        Route::post('/mkesh/debit_status', [MkeshPaymentController::class, 'debit_status']);
        Route::post('/mkesh/callback', [MkeshPaymentController::class, 'callback']);

        Route::post('/mpesa/debit_request', [MpesaPaymentController::class, 'debit_request']);
        Route::post('/mpesa/debit_status', [MpesaPaymentController::class, 'debit_status']);
        Route::post('/mpesa/callback', [MpesaPaymentController::class, 'callback']);

        Route::post('/emola/debit_request', [EmolaPaymentController::class, 'debit_request']);
        Route::post('/emola/debit_status', [EmolaPaymentController::class, 'debit_status']);
        Route::post('/emola/callback', [EmolaPaymentController::class, 'callback']);
    });
});

