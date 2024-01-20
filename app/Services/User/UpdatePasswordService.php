<?php

namespace App\Services\User;

use App\Events\AuthPasswordChanged;
use App\Exceptions\InvalidPasswordException;

class UpdatePasswordService extends AbstractUserService
{
	public function handle(int $userId, string $oldPassword, string $password): void
	{
		$user = $this->repository->getById($userId);

		if (!$this->checkPassword($oldPassword, $user->password)) {
			throw new InvalidPasswordException('Old password is incorrect');
		}

		$user = $this->repository->update(
			id: $userId,
			password: $this->hashPassword($password),
		);

        event(new AuthPasswordChanged($user));
	}
}