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

it('should fails to register a new user with invalid data', function () {
    $data = [
        'name' => '',
        'email' => 'invalid-email',
        'password' => '',
        'password_confirmation' => 'not_matching',
    ];

    $response = $this->postJson('/api/register', $data);

    expect($response)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['name', 'email', 'password', 'password_confirmation']);
});

it('should login a user with correct credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $data = [
        'email' => 'test@example.com',
        'password' => 'password',
    ];

    $response = $this->post('/api/login', $data);

    expect($response)
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure(['access_token']);
});

