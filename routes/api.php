<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\Webhooks\AsaasPaymentWebhookController;
use Illuminate\Support\Facades\Route;

// AUTH LOGIN | REGISTER
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    // AUTH USER
    Route::post('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    // PAYMENTS
    Route::apiResource('payments', PaymentController::class)
        ->except('update', 'destroy')
        ->names('payments');

    Route::get('payments/{payment}/pixQrCode', [PaymentController::class, 'getPixQrCode']);
});

// ASAAS PAYMENT WEBHOOK
Route::post('payments/asaas/webhook', [AsaasPaymentWebhookController::class, 'handle']);
