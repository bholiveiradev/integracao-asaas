<?php

use App\Models\User;
use Illuminate\Http\Response;
use Tests\Traits\MockPaymentGateway;

uses(MockPaymentGateway::class);

it('should register a new user', function () {
    $data = [
        'name'                  => fake()->name,
        'email'                 => fake()->unique()->safeEmail,
        'password'              => 'password',
        'password_confirmation' => 'password',
        'cpf_cnpj'              => fake()->cpf(), // format: 059.949.230-95 or 05994923095
        'phone'                 => fake()->landlineNumber(), // format: (11) 9999-9999
        'mobile_phone'          => fake()->cellphoneNumber(), // format: (11) 99999-9999
    ];

    $this->mockPaymentGatewayWithSuccess($data);

    $response = $this->post('/api/register', $data);

    expect($response)->assertStatus(Response::HTTP_CREATED);

    $this->assertDatabaseHas('payment_gateway_settings', [
            'name'              => 'Testing',
            'gateway_client_id' => '123'
        ]);
});

it('should fails to register a new user with invalid data', function () {
    $data = [
        'name'                  => '',
        'email'                 => 'invalid-email',
        'password'              => '',
        'password_confirmation' => 'not_matching',
        'cpf_cnpj'              => '99999999999',
        'phone'                 => '1199999999',
        'mobile_phone'          => '11999999999',
    ];

    $response = $this->postJson('/api/register', $data);

    expect($response)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['name', 'email', 'password', 'password_confirmation']);
});

it('should login a user with correct credentials', function () {
    User::factory()->create([
        'email'     => 'test@example.com',
        'password'  => bcrypt('password'),
    ]);

    $data = [
        'email'     => 'test@example.com',
        'password'  => 'password',
    ];

    $response = $this->post('/api/login', $data);

    expect($response)
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure(['access_token']);
});

it('shoud fails to login user with incorrect credentials', function () {
    User::factory()->create([
        'email'     => 'test@example.com',
        'password'  => bcrypt('password'),
    ]);

    $data = [
        'email'     => 'test@example.com',
        'password'  => 'wrongpassword',
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

it('should fail to logout if user is not logged in', function () {
    $response = $this->postJson('/api/logout');

    expect($response)
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson(['message' => 'Unauthenticated.']);
});
