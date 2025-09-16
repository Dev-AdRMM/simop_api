<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MkeshPaymentController;
use App\Http\Controllers\Api\EmolaPaymentController;
use App\Http\Controllers\Api\MpesaPaymentController;

#Api Payment

#mpesa
Route::post('/process-payment', [MpesaPaymentController::class, 'processPayment'])->name('process.payment');

Route::prefix('v1')->group(function () {
    Route::post('/mkesh/debit', [MkeshPaymentController::class, 'debit']);
    Route::post('/mkesh/status', [MkeshPaymentController::class, 'status']);
    Route::post('/mkesh/callback', [MkeshPaymentController::class, 'callback']);

    Route::post('/mpesa/debit', [MpesaPaymentController::class, 'debit']);
    Route::post('/mpesa/status', [MpesaPaymentController::class, 'status']);
    Route::post('/mpesa/callback', [MpesaPaymentController::class, 'callback']);

    Route::post('/emola/debit', [EmolaPaymentController::class, 'debit']);
    Route::post('/emola/status', [EmolaPaymentController::class, 'status']);
    Route::post('/emola/callback', [EmolaPaymentController::class, 'callback']);
});