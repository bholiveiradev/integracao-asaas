<?php

use App\Models\{Payment, User};

it('should return the list of payments', function () {
    $user = User::factory()->create();

    $client = $user->client()->create([
        'cpf_cnpj' => '56442819060',
        'phone' => '1199999999',
        'mobile_phone' => '11999999999',
        'email' => 'test@example.com',
    ]);

    Payment::factory(10)->create([
        'client_id' => $client->id,
        'gateway_name' => 'testing'
    ]);

    $this->actingAs($user);

    $response = $this->get('/api/payments');

    expect($response)->assertStatus(200);
});
