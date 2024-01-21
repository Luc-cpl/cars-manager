<?php

namespace App\Services\User;

use App\Exceptions\InvalidPasswordException;

class VerifyUserPasswordService extends AbstractUserService
{
	public function handle(int $userId, string $password): void
	{
		$user = $this->repository->getById($userId);

		if (!$this->checkPassword($password, $user->password)) {
			throw new InvalidPasswordException('Incorrect password');
		}
	}
}