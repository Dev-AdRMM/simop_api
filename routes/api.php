<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MkeshCallbackController;
use App\Http\Controllers\Api\MkeshPaymentController;
use App\Http\Controllers\Api\MpesaPaymentController;


#Api Payment
// Route::post('/mkesh/callback', [MkeshCallbackController::class, 'handle']);

#mpesa
Route::post('/process-payment', [MpesaPaymentController::class, 'processPayment'])->name('process.payment');


Route::prefix('v1/mkesh')->group(function () {
    Route::post('/debit', [MkeshController::class, 'debit']);
    Route::post('/status', [MkeshController::class, 'status']);
    Route::post('/callback', [MkeshController::class, 'callback']);
});