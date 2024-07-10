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

it('shoud fails to login user with incorrect credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $data = [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ];

    $response = $this->postJson('/api/login', $data);

    expect($response)
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson(['message' => 'Invalid credentials.']);
});

it('should returns the authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/api/me');

    expect($response)
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email
        ]);
});

it('should logout the user', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/api/logout');

    expect($response)
        ->assertStatus(Response::HTTP_OK)
        ->assertJson(['message' => 'Successfully logged out.']);
});
