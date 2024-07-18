<?php

use App\Models\{Payment, User, Client};
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    DB::beginTransaction();
});

afterEach(function () {
    DB::rollBack();
});

it('should return the paginated list of payments', function () {
    $user = User::factory()->create();

    $client = $user->client()->create([
        'cpf_cnpj'      => fake()->cpf(),
        'phone'         => fake()->landlineNumber(),
        'mobile_phone'  => fake()->cellphoneNumber(),
        'email'         => $user->email,
    ]);

    Payment::factory(10)->create([
        'client_id'     => $client->id,
        'gateway_name'  => 'Testing',
        'processing'    => false,
    ]);

    $response = $this->actingAs($user)->get('/api/payments');

    expect($response)->assertStatus(200)
                        ->assertJsonStructure([
                            'data',
                            'links',
                            'meta',
                        ]);
});

it('should return the payment by id', function () {
    $user = User::factory()->create();

    $client = $user->client()
        ->create([
            'cpf_cnpj'      => fake()->cpf(), // format: 059.949.230-95 or 05994923095
            'phone'         => fake()->landlineNumber(), // format: (11) 9999-9999
            'mobile_phone'  => fake()->cellphoneNumber(), // format: (11) 99999-9999
            'email'         => $user->email,
        ]);

    $payment = Payment::factory()->create([
        'client_id'     => $client->id,
        'processing'    => false,
    ]);

    $response = $this->actingAs($user)->get("/api/payments/{$payment->id}");

    expect($response)->assertStatus(Response::HTTP_OK);
});

it('should return 404 error on not found payment', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get("/api/payments/invalid-payment-id");

    expect($response)->assertStatus(Response::HTTP_NOT_FOUND);
});

it('should return 403 error on not authorized payment view', function () {
    $user = User::factory()->create();

    $client = $user->client()
        ->create([
            'cpf_cnpj'      => fake()->cpf(), // format: 059.949.230-95 or 05994923095
            'phone'         => fake()->landlineNumber(), // format: (11) 9999-9999
            'mobile_phone'  => fake()->cellphoneNumber(), // format: (11) 99999-9999
            'email'         => $user->email,
        ]);

    $payment = Payment::factory()->create([
        'client_id'     => $client->id,
        'processing'    => false,
    ]);

    $otherUser = User::factory()->create();
    $otherUser->client()->create();

    $response = $this->actingAs($otherUser)->get("/api/payments/{$payment->id}");

    expect($response)->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should create a payment with valid data', function () {
    $user = User::factory()->create();

    $user->client()->create([
        'cpf_cnpj'      => fake()->cpf(),
        'phone'         => fake()->landlineNumber(),
        'mobile_phone'  => fake()->cellphoneNumber(),
        'email'         => $user->email,
    ]);

    $paymentData = [
        'gateway_name'      => 'Testing',
        'reference'         => 'example_reference',
        'description'       => 'Test payment',
        'amount'            => 1000.00,
        'billing_type'      => 'BOLETO',
        'status'            => 'PENDING',
        'installment_count' => 1,
        'external_url'      => 'http://example.com',
    ];

    $response = $this->actingAs($user)->postJson('/api/payments', $paymentData);

    expect($response)->assertStatus(Response::HTTP_CREATED);
});

it('should return 422 error on creating payment with invalid data', function () {
    $user = User::factory()->create();

    $user->client()->create([
        'cpf_cnpj'      => fake()->cpf(),
        'phone'         => fake()->landlineNumber(),
        'mobile_phone'  => fake()->cellphoneNumber(),
        'email'         => $user->email,
    ]);

    $paymentData = [
        'description'       => 123,
        'amount'            => -1,
        'billing_type'      => 'INVALID_TYPE',
        'installment_count' => 13,
        'installment_value' => 1000000000000,
        'due_date'          => 'invalid-date',
    ];

    $response = $this->actingAs($user)->postJson('/api/payments', $paymentData);

    expect($response)->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 error when billing type is CREDIT_CARD and invalid data', function () {
    $user = User::factory()->create();

    $user->client()->create([
        'cpf_cnpj'      => fake()->cpf(),
        'phone'         => fake()->landlineNumber(),
        'mobile_phone'  => fake()->cellphoneNumber(),
        'email'         => $user->email,
    ]);

    $paymentData = [
        'description'           => 123,
        'amount'                => -1,
        'billing_type'          => 'CREDIT_CARD',
        'installment_count'     => 13,
        'installment_value'     => 1000000000000,
        'due_date'              => 'invalid-date',
        'card_number'           => '1234abcd5678efgh',
        'card_holder_name'      => '',
        'card_expiration'       => '01/1999',
        'cvv'                   => '1234',
        'name'                  => 456,
        'email'                 => 'invalid-email',
        'cpf_cnpj'              => '123456789',
        'postal_code'           => '12345',
        'address_number'        => '12345678901',
        'address_complement'    => str_repeat('a', 256),
        'phone'                 => '12345',
        'mobile_phone'          => '1234567',
        'remote_ip'             => 'invalid-ip',
    ];

    $response = $this->actingAs($user)->postJson('/api/payments', $paymentData);

    expect($response)->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should handles exceptions during payment creation', function () {
    Event::fake();

    Mockery::mock(Client::class, function ($mock) {
        $mock->shouldReceive('payments')
             ->andThrow(new \Exception('Simulated error'));
    });

    $user = User::factory()->create();

    $client = $user->client()->create([
        'cpf_cnpj'      => fake()->cpf(),
        'phone'         => fake()->landlineNumber(),
        'mobile_phone'  => fake()->cellphoneNumber(),
        'email'         => $user->email,
    ]);

    $paymentData = [
        'gateway_name'      => 'Testing',
        'reference'         => 'example_reference',
        'description'       => 'Test payment',
        'amount'            => 1000.00,
        'billing_type'      => 'BOLETO',
        'status'            => 'PENDING',
        'installment_count' => 1,
        'external_url'      => 'http://example.com',
    ];

    $response = $this->actingAs($user)
                     ->postJson('/api/payments', $paymentData);

    expect($response)->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);

    $this->assertDatabaseMissing('payments', [
        'client_id' => $client->id
    ]);
});
