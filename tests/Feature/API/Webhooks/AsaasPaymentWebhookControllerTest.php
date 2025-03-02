<?php

use App\Models\Payment;
use App\Models\PaymentGatewaySetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup mock data and environment
    config(['asaas.webhook_signature' => 'valid_signature']);
});

it('should reject requests with invalid signature', function () {
    $response = $this->postJson('/api/payments/asaas/webhook', [], ['asaas-access-token' => 'invalid_signature']);
    expect($response)->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should accept requests with valid signature', function () {
    $response = $this->postJson('/api/payments/asaas/webhook', [], ['asaas-access-token' => 'valid_signature']);
    expect($response)->assertStatus(Response::HTTP_OK);
});

it('should create a payment when PAYMENT_CREATED event is received', function () {
    $user = User::factory()->create();

    $customer = $user->customer()->create([
        'cpf_cnpj'      => fake()->cpf(),
        'phone'         => fake()->landlineNumber(),
        'mobile_phone'  => fake()->cellphoneNumber(),
        'email'         => $user->email,
    ]);

    PaymentGatewaySetting::factory()->create([
        'name'              => 'Testing',
        'gateway_customer_id' => '123456',
        'customer_id'         => $customer->id
    ]);

    $data = [
        'event' => 'PAYMENT_CREATED',
        'payment' => [
            'id' => 'payment_id',
            'customer' => '123456',
            'description' => 'Payment Description',
            'value' => 1000,
            'billingType' => 'boleto',
            'status' => 'created',
            'dueDate' => '2024-08-01',
            'installmentNumber' => 1,
            'confirmedDate' => '2024-07-17',
            'bankSlipUrl' => 'https://example.com/boleto',
        ],
    ];

    $this->postJson('/api/payments/asaas/webhook', $data, ['asaas-access-token' => 'valid_signature']);

    $payment = Payment::where('reference', 'payment_id')->first();
    expect($payment)->not()->toBeNull();
    expect($payment->description)->toEqual('Payment Description');
    expect($payment->amount)->toEqual(1000);
});

it('should update a payment when PAYMENT_CONFIRMED event is received', function () {
    $user = User::factory()->create();

    $customer = $user->customer()->create([
        'cpf_cnpj'      => fake()->cpf(),
        'phone'         => fake()->landlineNumber(),
        'mobile_phone'  => fake()->cellphoneNumber(),
        'email'         => $user->email,
    ]);

    $payment = Payment::factory()->create([
        'customer_id'     => $customer->id,
        'reference'     => 'payment_id',
        'processing'    => false,
    ]);

    $data = [
        'event' => 'PAYMENT_CONFIRMED',
        'payment' => [
            'id' => 'payment_id',
            'description' => 'Updated Description',
            'value' => 1200,
            'billingType' => 'boleto',
            'status' => 'confirmed',
            'dueDate' => '2024-08-01',
            'installmentNumber' => 1,
            'confirmedDate' => '2024-07-17',
            'bankSlipUrl' => 'https://example.com/updated_boleto',
        ],
    ];

    $this->postJson('/api/payments/asaas/webhook', $data, ['asaas-access-token' => 'valid_signature']);

    $payment->refresh();
    expect($payment->description)->toEqual('Updated Description');
    expect($payment->amount)->toEqual(1200);
});

it('should delete a payment when PAYMENT_DELETED event is received', function () {
    $user = User::factory()->create();

    $customer = $user->customer()->create([
        'cpf_cnpj'      => fake()->cpf(),
        'phone'         => fake()->landlineNumber(),
        'mobile_phone'  => fake()->cellphoneNumber(),
        'email'         => $user->email,
    ]);

    $payment = Payment::factory()->create([
        'customer_id'     => $customer->id,
        'reference'     => 'payment_id',
        'processing'    => false,
    ]);

    $data = [
        'event' => 'PAYMENT_DELETED',
        'payment' => [
            'id' => 'payment_id',
        ],
    ];

    $this->postJson('/api/payments/asaas/webhook', $data, ['asaas-access-token' => 'valid_signature']);

    $payment = Payment::where('reference', 'payment_id')->first();
    expect($payment)->toBeNull();
});
