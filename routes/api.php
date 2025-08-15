<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MkeshCallbackController;
use App\Http\Controllers\Api\MpesaPaymentController;


#Api Payment
Route::post('/mkesh/callback', [MkeshCallbackController::class, 'handle']);

#mpesa
Route::post('/process-payment', [MpesaPaymentController::class, 'processPayment'])->name('process.payment');
