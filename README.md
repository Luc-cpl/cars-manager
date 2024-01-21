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

## Tests

You can run the tests with the following command:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail test
```

## Endpoints

All routes needs a JWT token in `Authorization` header (bearer auth) except:
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

Should have the current auth token in Auth header (bearer), this will return a new valid token.


### POST /logout

Invalidate current JWT

## API User Endpoints

### GET /api/me

Show current user

### PUT /api/me

update current user
If you want to update the password or email, you need to send the `password_confirmation` field with the current password.

```json
{
  "password_confirmation": "your_current_password", // *required with password and email
  "password": "updated_password",
  "email": "updated_email",
  "name": "Updated name"
}
```

### GET /api/users

List the existing users
Accepted args:
* `page` (int) - Page number
* `limit` (int) - Number of items per page (default is 15)
* `deleted (bool)` - Return only soft deleted users

### GET /api/users/{id}

Retrieve a specific user by ID

### PUT /api/users/{id}

Update a specific user by ID.
If you want to update the password or email, you need to send the `password_confirmation` field with your current password.

```json
{
  "password_confirmation": "your_current_password", // *required with password and email
  "password": "updated_password",
  "email": "updated_email",
  "name": "Updated name"
}
```

### DELETE /api/users/{id}

Delete a specific user by ID.
This is a soft delete, so all the user data will be kept in the database.

### POST /api/users/{id}/restore

Restore a specific user by ID.

## API Cars Endpoints

### GET /api/cars

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


### POST /api/cars

Create a new car for current user

```json
{
  "name": "your car name" // *required
}
```

### GET /api/cars/{id}

Retrieve the car by ID

### PUT /api/cars/{id}

Update the car by ID

```json
{
  "name": "your car name" // *required
}
```

### DELETE /api/cars/{id}

Delete the car by ID (The car is soft delete here)

### POST /api/cars/{id}/restore

Restore the soft deleted car

### DELETE /api/cars/{id}/force-delete

Completely removes the car by its ID

### GET /api/cars/{id}/associate

Get the list of users associated with this car

### POST /api/cars/{id}/associate

Associate the current user with the car
It also accepts a `user_id` field allowing set another user.

```json
{
  "user_id": 1
}
```

### DELETE /api/cars/{id}/associate

Deletes the current user assocation with the car

### DELETE /api/cars/{id}/associate/{user_id}

Removev a user assocation with the car by user ID


## License

This application is licensed under the [MIT license](https://opensource.org/licenses/MIT).
