<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use InvalidArgumentException;

class CreateUserService extends AbstractUserService
{
	public function handle(string $name, string $email, string $password): User
	{
		$user = $this->repository->getByEmail($email);

		if ($user) {
			/** @todo add a custom exception */
			throw new InvalidArgumentException('User with this email already exists');
		}

		$user = $this->repository->create(
			name: $name,
			email: $email,
			password: $this->hashPassword($password),
		);

        event(new Registered($user));

		return $user;
	}
}