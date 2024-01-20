## Introduction

You can run the dev environment and migrations with Laravel Sail (Docker needed).

Fist, you need to install the dependencies and set the environment.

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Then, you can run the dev environment and migrations.

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

## Endpoints

**Every request to the API should have the `Accept` as `application/json`.**

### POST /register

Register a new user.

```json
{
  "email": "youremail@email.com",
  "password": "yourpassword",
  "name": "yourname"
}
```

### POST /login

/** @todo add the JWT */
Login with an existing user retrieving a JWT token

```json
{
  "email": "youremail@email.com",
  "password": "yourpassword"
}
```

### POST /forgot-password

Send an email with a link to reset the password.

```json
{
  "email": "youremail@email.com"
}
```

### POST /reset-password

Reset the user password

// @todo get the request data
'token' => ['required'],
'email' => ['required', 'email'],
'password' => ['required', Rules\Password::defaults()],


// @todo add this routes
get '/me'
post '/forgot-password'
post '/reset-password'
post '/update-password'
get '/verify-email/{id}/{hash}'
post '/email/verification-notification'
post '/logout'


## License

This application is licensed under the [MIT license](https://opensource.org/licenses/MIT).
