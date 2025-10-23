<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MkeshPaymentController;
use App\Http\Controllers\Api\EmolaPaymentController;
use App\Http\Controllers\Api\MpesaPaymentController;

use App\Http\Controllers\Api\WalletManagementController;

use App\Http\Controllers\Auth\AuthTokenController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;


Route::prefix('v1/auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/verify', [VerificationController::class, 'verify']);
    Route::post('/token', [AuthTokenController::class, 'getToken']);
});

Route::prefix('v1')->middleware(['simop.user'])->group(function () {
    #Gestão de Carteiras - SIMOP
    Route::prefix('wallets')->group(function () {
        Route::get('/', [WalletManagementController::class, 'index']);
        Route::post('/', [WalletManagementController::class, 'store']);

        Route::get('/{wallet}', [WalletManagementController::class, 'show']);
        Route::put('/{wallet}', [WalletManagementController::class, 'update']);
        Route::delete('/{wallet}', [WalletManagementController::class, 'destroy']);

        Route::post('/{wallet}/suspend', [WalletManagementController::class, 'suspend']);
        Route::post('/{wallet}/activate', [WalletManagementController::class, 'activate']);
    });
});

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
        Route::get('/', [WalletManagementController::class, 'index']);
        Route::post('/', [WalletManagementController::class, 'store']);

        Route::get('/{wallet}', [WalletManagementController::class, 'show']);
        Route::put('/{wallet}', [WalletManagementController::class, 'update']);
        Route::delete('/{wallet}', [WalletManagementController::class, 'destroy']);

        Route::post('/{wallet}/suspend', [WalletManagementController::class, 'suspend']);
        Route::post('/{wallet}/activate', [WalletManagementController::class, 'activate']);
    });
});




