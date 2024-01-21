<?php

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $this->assertAuthenticated();

    $response->assertStatus(200)->assertJsonStructure([
        'access_token',
        'token_type',
        'expires_in'
    ]);
});
