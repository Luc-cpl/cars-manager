<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\Interfaces\UsersRepositoryInterface;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Auth\Events\Registered;

class CreateUserService implements ServiceInterface
{
	use Partials\PasswordHashTrait;

	public function __construct(
		private UsersRepositoryInterface $usersRepository
	) {
	}

	/**
	 * @return User
	 */
	public function handle(array ...$data)
	{
		$user = $this->usersRepository->create(
			name: $data['name'],
			email: $data['email'],
			password: $this->hashPassword($data['password']),
		);

        event(new Registered($user));

		return $user;
	}
}