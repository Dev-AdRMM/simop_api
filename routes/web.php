<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MkeshCallbackController;

Route::get('/', function () {
    return view('welcome');
});

