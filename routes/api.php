<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MkeshPaymentController;
use App\Http\Controllers\Api\EmolaPaymentController;
use App\Http\Controllers\Api\MpesaPaymentController;

use App\Http\Controllers\Api\WalletManagementController;

Route::prefix('v1')->middleware(['verify.simop.apikey'])->group(function () {

    #Pagamentos - Rotas das Operadoras
    Route::prefix('mkesh')->group(function () {
        Route::post('/debit_request', [MkeshPaymentController::class, 'debit_request']);
        Route::post('/debit_status', [MkeshPaymentController::class, 'debit_status']);
        Route::post('/callback', [MkeshPaymentController::class, 'callback']);
    });

    Route::prefix('mpesa')->group(function () {
        Route::post('/debit_request', [MpesaPaymentController::class, 'debit_request']);
        Route::post('/debit_status', [MpesaPaymentController::class, 'debit_status']);
        Route::post('/callback', [MpesaPaymentController::class, 'callback']);
    });

    Route::prefix('emola')->group(function () {
        Route::post('/debit_request', [EmolaPaymentController::class, 'debit_request']);
        Route::post('/debit_status', [EmolaPaymentController::class, 'debit_status']);
        Route::post('/callback', [EmolaPaymentController::class, 'callback']);
    });

    #Gestão de Carteiras - SIMOP
    Route::prefix('wallets')->group(function () {
        Route::get('/', [WalletController::class, 'index']);
        Route::post('/', [WalletController::class, 'store']);
        Route::get('/{wallet}', [WalletController::class, 'show']);
        Route::put('/{wallet}', [WalletController::class, 'update']);
        Route::delete('/{wallet}', [WalletController::class, 'destroy']);

        Route::post('/{wallet}/suspend', [WalletController::class, 'suspend']);
        Route::post('/{wallet}/activate', [WalletController::class, 'activate']);
    });
});




