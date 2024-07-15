<?php

use App\Models\{Payment, User};
use Illuminate\Http\Response;

it('should return the paginated list of payments', function () {
    $user = User::factory()->create();

    $client = $user->client()
        ->create([
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

    $this->actingAs($user);

    $response = $this->get('/api/payments');

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

    $this->actingAs($user);

    $response = $this->get("/api/payments/{$payment->id}");

    expect($response)->assertStatus(Response::HTTP_OK);
});

it('should return 404 error on not found payment', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get("/api/payments/invalid-payment-id");

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

    $unauthorizedUser = User::factory()->create();
    $unauthorizedUser->client()->create();

    $this->actingAs($unauthorizedUser);

    $response = $this->get("/api/payments/{$payment->id}");

    expect($response)->assertStatus(Response::HTTP_FORBIDDEN);
});
