<?php

use App\Models\{Payment, User};

it('should return the list of payments', function () {
    $user = User::factory()->create();

    $client = $user->client()->create([
        'cpf_cnpj' => fake()->cpf(),
        'phone' => fake()->landlineNumber(),
        'mobile_phone' => fake()->cellphoneNumber(),
        'email' => $user->email,
    ]);

    Payment::factory(10)->create([
        'client_id' => $client->id,
        'gateway_name' => 'testing'
    ]);

    $this->actingAs($user);

    $response = $this->get('/api/payments');

    expect($response)->assertStatus(200);
});
