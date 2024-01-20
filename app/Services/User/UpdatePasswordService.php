<?php

namespace App\Services\User;

use App\Events\Auth\PasswordChanged;
use App\Exceptions\InvalidPasswordException;
use App\Repositories\Interfaces\UsersRepositoryInterface;
use App\Services\Interfaces\ServiceInterface;

class UpdatePasswordService implements ServiceInterface
{
	use Partials\PasswordHashTrait;

	public function __construct(
		private UsersRepositoryInterface $usersRepository
	) {
	}

	/**
	 * @param int $userId
	 * @param string $oldPassword
	 * @param string $password
	 * @return User
	 */
	public function handle(...$args)
	{
		[$userId, $oldPassword, $data] = $args;

		$user = $this->usersRepository->getById($userId);

		if (!$this->checkPassword($oldPassword, $user->password)) {
			throw new InvalidPasswordException('Old password is incorrect');
		}

		$user = $this->usersRepository->update(
			id: $userId,
			password: $this->hashPassword($data['password']),
		);

        event(new PasswordChanged($user));

		return $user;
	}
}