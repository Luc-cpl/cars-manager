<?php

use App\Models\User;

test('users can authenticate using the login route', function () {

    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)->assertJsonStructure([
        'access_token',
        'token_type',
        'expires_in'
    ]);

    $this->assertAuthenticated('api');
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});


test('users can logout', function () {
    $user = User::factory()->create();
    $token = auth()->login($user);

    $this->withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
    ]);

    $this->post('/logout')->assertStatus(200);
    $this->post('/logout')->assertStatus(401);
});

test('user can refresh token', function () {
    $user = User::factory()->create();
    $token = auth()->login($user);

    $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response = $this->post('/refresh');

    $this->assertNotEquals($token, $response['access_token']);
    $this->assertAuthenticated('api');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'access_token',
        'token_type',
        'expires_in'
    ]);
});