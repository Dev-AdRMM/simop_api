<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MkeshCallbackController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/mkesh/callback', [MkeshCallbackController::class, 'handle']);