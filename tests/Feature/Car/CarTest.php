<?php

use App\Models\Car;
use App\Models\User;

test('can not go through /api/cars routes without authorization', function () {
	$this->withHeaders([
		'Accept' => 'application/json',
	]);
	$this->get('/api/cars')->assertStatus(401);
	$this->post('/api/cars')->assertStatus(401);
	$this->get('/api/cars/1')->assertStatus(401);
	$this->put('/api/cars/1')->assertStatus(401);
	$this->delete('/api/cars/1')->assertStatus(401);
	$this->post('/api/cars/1/restore')->assertStatus(401);
	$this->get('/api/cars/1/associate')->assertStatus(401);
	$this->post('/api/cars/1/associate')->assertStatus(401);
	$this->delete('/api/cars/1/associate')->assertStatus(401);
	$this->delete('/api/cars/1/associate/1')->assertStatus(401);
});

test('can list cars', function () {
	$user = User::factory()->create();
	Car::factory()->count(5)->create(['owner_id' => $user->id]);

	$this->actingAs($user)
		->get('/api/cars')
		->assertStatus(200)
		->assertJsonStructure([
			'*' => [
				'id',
				'name',
				'owner_id',
				'created_at',
				'updated_at',
				'deleted_at',
			]
		]);
});

test('can list a specific user cars', function () {
	$user = User::factory()->create();
	$anotherUser = User::factory()->create();
	Car::factory()->count(5)->create(['owner_id' => $user->id]);
	Car::factory()->count(5)->create(['owner_id' => $anotherUser->id]);

	$this->actingAs($user)
		->get('/api/cars?owner_id=' . $user->id)
		->assertStatus(200)
		->assertJsonCount(5);
});

test('can paginate cars', function () {
	$user = User::factory()->create();
	Car::factory()->count(5)->create(['owner_id' => $user->id]);

	$this->actingAs($user)
		->get('/api/cars?page=1&limit=2')
		->assertStatus(200)
		->assertJsonCount(2, '*');

	$this->actingAs($user)
		->get('/api/cars?page=3&limit=2')
		->assertStatus(200)
		->assertJsonCount(1, '*');
});

test('can create a car', function () {
	$user = User::factory()->create();

	$this->actingAs($user)
		->post('/api/cars', [
			'name' => 'My car'
		])
		->assertStatus(201)
		->assertJsonStructure([
			'id',
			'name',
			'owner_id',
			'created_at',
			'updated_at',
		]);
});

test('can show a car', function () {
	$user = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $user->id]);

	$this->actingAs($user)
		->get('/api/cars/' . $car->id)
		->assertStatus(200)
		->assertJsonStructure([
			'id',
			'name',
			'owner_id',
			'created_at',
			'updated_at',
			'deleted_at',
		]);
});

test('can update a car', function () {
	$user = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $user->id]);

	$this->actingAs($user)
		->put('/api/cars/' . $car->id, [
			'name' => 'My new car name'
		])
		->assertStatus(200)
		->assertJsonStructure([
			'id',
			'name',
			'owner_id',
			'created_at',
			'updated_at',
			'deleted_at',
		])->assertJson([
			'name' => 'My new car name'
		]);
});

test('can delete a car', function () {
	$user = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $user->id]);

	$this->actingAs($user)
		->delete('/api/cars/' . $car->id)
		->assertStatus(200);

	$this->assertSoftDeleted('cars', [
		'id' => $car->id,
	]);
});

test('can restore a car', function () {
	$user = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $user->id]);
	$car->delete();

	$this->actingAs($user)
		->post('/api/cars/' . $car->id . '/restore')
		->assertStatus(200);

	$this->assertDatabaseHas('cars', [
		'id' => $car->id,
		'deleted_at' => null,
	]);
});

test('can force delete a car', function () {
	$user = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $user->id]);

	$this->actingAs($user)
		->delete('/api/cars/' . $car->id . '/force-delete')
		->assertStatus(200);

	$this->assertDatabaseMissing('cars', [
		'id' => $car->id,
	]);
});

test('can associate a car with current user', function () {
	$user = User::factory()->create();
	$carOwner = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $carOwner->id]);

	$this->actingAs($user)
		->post('/api/cars/' . $car->id . '/associate')
		->assertStatus(200);

	$this->assertDatabaseHas('user_associated_cars', [
		'car_id' => $car->id,
		'user_id' => $user->id,
	]);
});

test('can associate a car with another user', function () {
	$user = User::factory()->create();
	$anotherUser = User::factory()->create();
	$carOwner = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $carOwner->id]);

	$this->actingAs($user)
		->post('/api/cars/' . $car->id . '/associate', [
			'user_id' => $anotherUser->id
		])
		->assertStatus(200);

	$this->assertDatabaseHas('user_associated_cars', [
		'car_id' => $car->id,
		'user_id' => $anotherUser->id,
	]);
});

test('can not associate a car with his owner', function () {
	$user = User::factory()->create();
	$carOwner = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $carOwner->id]);

	$this->actingAs($user)
		->post('/api/cars/' . $car->id . '/associate', [
			'user_id' => $carOwner->id
		])
		->assertStatus(422);
});

test('can disassociate a car with current user', function () {
	$user = User::factory()->create();
	$carOwner = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $carOwner->id]);
	$car->associatedUsers()->attach($user->id);

	$this->actingAs($user)
		->delete('/api/cars/' . $car->id . '/associate')
		->assertStatus(200);

	$this->assertDatabaseMissing('user_associated_cars', [
		'car_id' => $car->id,
		'user_id' => $user->id,
	]);
});

test('can disassociate a car with another user', function () {
	$user = User::factory()->create();
	$anotherUser = User::factory()->create();
	$carOwner = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $carOwner->id]);
	$car->associatedUsers()->attach($anotherUser->id);

	$this->actingAs($user)
		->delete('/api/cars/' . $car->id . '/associate/' . $anotherUser->id)
		->assertStatus(200);

	$this->assertDatabaseMissing('user_associated_cars', [
		'car_id' => $car->id,
		'user_id' => $anotherUser->id,
	]);
});

test('can list associated cars', function () {
	$user = User::factory()->create();
	$carOwner = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $carOwner->id]);
	$car->associatedUsers()->attach($user->id);

	Car::factory()->count(2)->create(['owner_id' => $carOwner->id]);
	Car::factory()->count(2)->create(['owner_id' => $user->id]);

	$this->actingAs($user)
		->get('/api/cars?associated_id=' . $user->id)
		->assertStatus(200)
		->assertJsonCount(1)
		->assertJson([
			[
				'id' => $car->id,
			]
		]);
});

test('can list the users associated with a car', function () {
	$user = User::factory()->create();
	$anotherUser = User::factory()->create();
	$carOwner = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $carOwner->id]);
	$car->associatedUsers()->attach($user->id);
	$car->associatedUsers()->attach($anotherUser->id);

	$this->actingAs($user)
		->get('/api/cars/' . $car->id . '/associate')
		->assertStatus(200)
		->assertJsonCount(2)
		->assertJson([
			[
				'id' => $user->id,
			],
			[
				'id' => $anotherUser->id,
			]
		]);
});

test('can delete a car when deleting the owner', function () {
	$user = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $user->id]);

	$user->forceDelete();

	$this->assertDatabaseMissing('cars', [
		'id' => $car->id,
	]);
});

test('can delete the association when deleting the user', function () {
	$user = User::factory()->create();
	$carOwner = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $carOwner->id]);
	$car->associatedUsers()->attach($user->id);

	$user->forceDelete();

	$this->assertDatabaseMissing('user_associated_cars', [
		'car_id' => $car->id,
		'user_id' => $user->id,
	]);
});

test('can delete the association when deleting the car', function () {
	$user = User::factory()->create();
	$carOwner = User::factory()->create();
	$car = Car::factory()->create(['owner_id' => $carOwner->id]);
	$car->associatedUsers()->attach($user->id);

	$car->forceDelete();

	$this->assertDatabaseMissing('user_associated_cars', [
		'car_id' => $car->id,
		'user_id' => $user->id,
	]);
});