<?php

use App\Models\User;

it('should return the list of payments', function () {
    $user = User::factory()->create();

    $user->client()->create([
        'cpf_cnpj' => '56442819060',
        'phone' => '1199999999',
        'mobile_phone' => '11999999999',
        'email' => 'test@example.com',
    ]);

    $this->actingAs($user);

    $response = $this->get('/api/payments');

    expect($response)->assertStatus(200);
});
