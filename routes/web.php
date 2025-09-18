<?php

use Illuminate\Support\Facades\Route;

// Admin Routes
Route::view('/', 'simop_serverSide/pages/dashboard/index');

#dashboard
Route::view('/dashboard/index', 'simop_serverSide/pages/dashboard/index');
Route::view('/dashboard/mpesa', 'simop_serverSide/pages/dashboard/mpesa');
Route::view('/dashboard/emola', 'simop_serverSide/pages/dashboard/emola');
Route::view('/dashboard/mkesh', 'simop_serverSide/pages/dashboard/mkesh');

#transations 
Route::view('/transactions/index', 'simop_serverSide/pages/transactions/index');
Route::view('/transactions/mpesa', 'simop_serverSide/pages/transactions/mpesa');
Route::view('/transactions/emola', 'simop_serverSide/pages/transactions/emola');
Route::view('/transactions/mkesh', 'simop_serverSide/pages/transactions/mkesh');

#wallets
Route::view('/wallet/emola/index', 'simop_serverSide/pages/wallets/index');
Route::view('/wallet/mpesa', 'simop_serverSide/pages/wallets/mpesa');
Route::view('/wallet/emola', 'simop_serverSide/pages/wallets/emola');
Route::view('/wallet/mkesh', 'simop_serverSide/pages/wallets/mkesh');

#suport Admin
Route::view('/suport/chat_box', 'simop_serverSide/pages/suport/chat_box');
Route::view('/suport/contacts', 'simop_serverSide/pages/suport/contacts');
Route::view('/suport/documentation', 'simop_serverSide/pages/suport/documentation');
Route::view('/suport/faq', 'simop_serverSide/pages/suport/faq');

#user management
Route::view('/user/profile', 'simop_serverSide/pages/auth/user_profile');

#auth 
Route::view('/auth/sign_in', 'simop_serverSide/pages/auth/sign_in');
Route::view('/auth/sign_up', 'simop_serverSide/pages/auth/sign_up');
Route::view('/auth/forgot_password', 'simop_serverSide/pages/auth/forgot_password');
Route::view('/auth/reset_password', 'simop_serverSide/pages/auth/reset_password');
Route::view('/auth/privacy', 'simop_serverSide/pages/auth/privacy');