<?php

use App\Models\User;

test('can not go through /api/users routes without authorization', function () {
	$this->withHeaders([
		'Accept' => 'application/json',
	]);
	$this->get('/api/users')->assertStatus(401);
	$this->get('/api/users/1')->assertStatus(401);
	$this->put('/api/users/1')->assertStatus(401);
	$this->delete('/api/users/1')->assertStatus(401);
	$this->post('/api/users/1/restore')->assertStatus(401);
});

test('can get all users', function () {
	$user = User::factory()->create();

	User::factory()->count(5)->create();

	// Create 2 users and delete them to check if they are not returned
	User::factory()->count(2)->create()->each(function (User $user) {
		$user->delete();
	});

	$response = $this->actingAs($user)->get('/api/users');

	$response->assertStatus(200)->assertJsonStructure([
		'*' => [
			'id',
			'name',
			'email',
			'email_verified_at',
			'created_at',
			'updated_at',
		]
	])->assertJsonMissing([
		'data' => [
			'*' => [
				'password',
			]
		]
	]);

	expect(count($response->json()))->toBe(6);
});

test('can get all deleted users', function () {
	$user = User::factory()->create();

	User::factory()->count(2)->create()->each(function ($user) {
		$user->delete();
	});

	$response = $this->actingAs($user)->get('/api/users?deleted=true');

	expect(count($response->json()))->toBe(2);
});

test('can get a single user', function () {
	$user = User::factory()->create();

	$response = $this->actingAs($user)->get('/api/users/' . $user->id);

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

test('can get a deleted user', function () {
	$user = User::factory()->create();
	$user->delete();

	$response = $this->actingAs($user)->get('/api/users/' . $user->id);
	expect($response['deleted_at'])->toBe($user->deleted_at->toJSON());
});

test('can update a user', function () {
    $user = User::factory()->create();
	$userToUpdate = User::factory()->create();

    $updatedUserData = [
        'name' => 'Updated Name',
        'email' => 'updatedemail@example.com',
		'password' => 'password',
		'password_confirmation' => 'password',
    ];

    $response = $this->actingAs($user)->put('/api/users/' . $userToUpdate->id, $updatedUserData);

    $response->assertStatus(200);

    expect($response['id'])->toBe($userToUpdate->id);
    expect($response['name'])->toBe($updatedUserData['name']);
    expect($response['email'])->toBe($updatedUserData['email']);

	$this->assertTrue((bool) auth()->attempt([
		'email' => $updatedUserData['email'],
		'password' => $updatedUserData['password'],
	]));
});

test('can not update a user sensitive data with no password confirmation', function () {
	$user = User::factory()->create();

	$updatedUserData = [
		'email' => 'updatedemail@email.com',
	];

	$this->withHeaders([
		'Accept' => 'application/json',
	]);

	$response = $this->actingAs($user)->put('/api/users/' . $user->id, $updatedUserData);
	$response->assertStatus(422);
});

test('can delete a user', function () {
	$user = User::factory()->create();
	$otherUser = User::factory()->create();

	$response = $this->actingAs($user)->delete('/api/users/' . $otherUser->id);

	$response->assertStatus(200);

	$this->assertSoftDeleted('users', [
		'id' => $otherUser->id,
	]);
});

test('can not delete yourself', function () {
	$user = User::factory()->create();

	$response = $this->actingAs($user)->delete('/api/users/' . $user->id);

	$response->assertStatus(422);
});

test('can restore a user', function () {
	$user = User::factory()->create();
	$user->delete();

	$response = $this->actingAs($user)->post('/api/users/' . $user->id . '/restore');

	$response->assertStatus(200);

	$this->assertDatabaseHas('users', [
		'id' => $user->id,
		'deleted_at' => null,
	]);
});
