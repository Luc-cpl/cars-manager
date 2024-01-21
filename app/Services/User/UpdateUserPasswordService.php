<?php

namespace App\Services\User;

use App\Events\UserPasswordChanged;

class UpdateUserPasswordService extends AbstractUserService
{
	public function handle(int $userId, string $password): void
	{
		$this->repository->update(
			id: $userId,
			password: $this->hashPassword($password),
		);

        event(new UserPasswordChanged($this->repository->getById($userId)));
	}
}