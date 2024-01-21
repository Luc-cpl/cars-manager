<?php

use App\Models\User;

test('check /api/me routes authorization', function () {
	$this->withHeaders([
		'Accept' => 'application/json',
	]);

	$this->get('/api/me')->assertStatus(401);
	$this->put('/api/me')->assertStatus(401);
});

test('can get current user', function () {
    $user = User::factory()->create();

	$response = $this->actingAs($user)->get('/api/me');

	$response->assertStatus(200)->assertJsonStructure([
		'id',
		'name',
		'email',
		'email_verified_at',
		'created_at',
		'updated_at',
	])->assertJsonMissing([
		'password',
	]);

	expect($response['id'])->toBe($user->id);
	expect($response['name'])->toBe($user->name);
	expect($response['email'])->toBe($user->email);
});

test('can update current user email', function () {
    $user = User::factory()->create();

    $updatedUserData = [
        'email' => 'updatedemail@example.com',
		'password' => 'password',
    ];

    $response = $this->actingAs($user)->put('/api/me', $updatedUserData);

    $response->assertStatus(200);

    expect($response['email'])->toBe($updatedUserData['email']);
});

test('can not update current user email with invalid password', function () {
	$user = User::factory()->create();

	$updatedUserData = [
		'email' => 'updatedemail@example.com',
		'password' => 'invalid_password',
    ];

	$response = $this->actingAs($user)->put('/api/me', $updatedUserData);
	$response->assertStatus(401);
	expect($response['code'])->toBe('invalid_password');
});

test('can update current user password', function () {
	$user = User::factory()->create();

	$updatedUserData = [
		'password' => 'password',
		'new_password' => 'new_password',
	];

	$this->actingAs($user)->put('/api/me', $updatedUserData);

	$this->assertTrue((bool) auth()->attempt([
		'email' => $user->email,
		'password' => $updatedUserData['new_password'],
	]));
});

test('can not update current user password with invalid password', function () {
	$user = User::factory()->create();

	$updatedUserData = [
		'password' => 'wrong_password',
		'new_password' => 'new_password',
	];

	$this->actingAs($user)->put('/api/me', $updatedUserData);

	$this->assertFalse((bool) auth()->attempt([
		'email' => $user->email,
		'password' => $updatedUserData['new_password'],
	]));
});

test('can update current user data', function () {
	$user = User::factory()->create();

	$updatedUserData = [
		'name' => 'Updated name',
	];

	$response = $this->actingAs($user)->put('/api/me', $updatedUserData);

	$response->assertStatus(200);

	expect($response['name'])->toBe($updatedUserData['name']);
});
