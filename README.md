## Introduction

You can easily run the dev environment and migrations with Laravel Sail (Docker needed).

Fist, you need to install the dependencies and set the environment.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

Then, you can run the dev environment and migrations.

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

## Endpoints

Every request to the API should have the header `Accept` with the value `application/json`.
All routes needs a JWT token in `Auth` header (bearer auth) except:
* POST /register
* POST /login

## Auth Endpoints

### POST /register

Register a new user and retrieves a logged in jwt.

```json
{
  "email": "youremail@email.com",
  "password": "yourpassword",
  "name": "yourname"
}
```

### POST /login

Login with an existing user retrieving a JWT token

```json
{
  "email": "youremail@email.com",
  "password": "yourpassword"
}
```

### POST /refresh

Should have the current auth token in Auth header (bearer), this will retturn a new valid token.


### POST /logout

Invalidate current JWT

## API User Endpoints

### GET /me

Show current user

### PUT /me

update current user

```json
{
  "password": "your_current_password", // *required
  "new_password": "updated_password",
  "email": "updated_email"
}
```

### GET /users

List the existing users
Accepted args:
* `page` (int) - Page number
* `limit` (int) - Number of items per page (default is 15)
* `deleted (bool)` - Return only soft deleted users

### GET /users/{id}

Retrieve a specific user by ID

### PUT /users/{id}

Update a specific user by ID.
If this request whas made by the same user, it will behave as the `/me` route (same arguments).

```json
{
  "password": "updated_password",
  "email": "updated_email"
}
```

### DELETE /users/{id}

Delete a specific user by ID.
This is a soft delete, so all the user data will be kept in the database.

### POST /users/{id}/restore

Restore a specific user by ID.

## API Cars Endpoints

### GET /cars

List the existing cars
Accepted args:
* `page` (int) - Page number
* `limit` (int) - Number of items per page (default is 15)
* `deleted (bool)` - Return only soft deleted users
* `owner_id` - The user ID of the owner
* `associated_id` - The ID of a user associated with the car

Examples:
* `/cars?owner_id=1` - Return all cars owned by user 1
* `/cars?owner_id=1&deleted` - Return all soft deleted cars owned by user 1
* `/cars?owner_id=1&page=2` - Returns the second page of cars owned by user 1
* `/cars?owner_id=1&associated_id=2` - Return all cars owned by user 1 and associated with user 2


### POST /cars

Create a new car for current user

```json
{
  "name": "your car name" // *required
}
```

### GET /cars/{id}

Retrieve the car by ID

### PUT /cars/{id}

Update the car by ID

```json
{
  "name": "your car name" // *required
}
```

### DELETE /cars/{id}

Delete the car by ID (The car is soft delete here)

### POST /cars/{id}/restore

Restore the soft deleted car

### DELETE /cars/{id}/force-delete

Completely removes the car by its ID

### GET /cars/{id}/associate

Get the list of users associated with this car

### POST /cars/{id}/associate

Associate the current user with the car
It also accepts a `user_id` field allowing set another user.

```json
{
  "user_id": 1
}
```

### DELETE /cars/{id}/associate

Deletes the current user assocation with the car

### DELETE /cars/{id}/associate/{user_id}

Removev a user assocation with the car by user ID


## License

This application is licensed under the [MIT license](https://opensource.org/licenses/MIT).
