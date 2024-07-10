<?php

use App\Models\User;
use Illuminate\Http\Response;

it('should register a new user', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->post('/api/register', $data);

    expect($response)->assertStatus(Response::HTTP_CREATED);
});
