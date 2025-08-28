<?php

use App\Models\User;

test('Auth: login and receive token', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['access_token', 'user']);
});

test('Auth: cannot login with incorrect credentials', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => '123456',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});


test('Auth: register and receive token', function () {
    $payload = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->postJson('/api/auth/register', $payload);
    $response->assertCreated();
    $response->assertJsonStructure(['access_token', 'user']);
    $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
});

test('Auth: cannot register with invalid data', function () {
    $payload = [
        'name' => '',
        'email' => 'johnexample.com',
        'password' => 'password',
        'password_confirmation' => 'passw0rd',
    ];

    $response = $this->postJson('/api/auth/register', $payload);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'email', 'password']);
});

test('Auth: logout and invalidate token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('laravel_api_token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/auth/logout');

    $response->assertNoContent();

    $this->app['auth']->forgetGuards();

    // Attempt to access a protected route
    $protectedResponse = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->getJson('/api/user');

    $protectedResponse->assertUnauthorized();
});
