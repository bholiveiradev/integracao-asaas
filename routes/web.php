<?php

use App\Http\Controllers\Web\{
    LoginController,
    PaymentController,
    RegisterController
};
use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.login');

// AUTH
Route::get('login', LoginController::class);
Route::get('register', RegisterController::class);

// PAYMENTS
Route::get('payments', [PaymentController::class, 'index']);
Route::get('payments/new', [PaymentController::class, 'create']);
Route::get('payments/{payment}', [PaymentController::class, 'show']);
Route::get('payments/{payment}/error', [PaymentController::class, 'error']);
