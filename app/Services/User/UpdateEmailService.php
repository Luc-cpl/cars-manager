<?php

namespace App\Services\User;

use App\Events\UserEmailChanged;

class UpdateEmailService extends AbstractUserService
{
	public function handle(int $userId, string $email): void
	{
		$this->repository->update(
			id: $userId,
			email: $email,
		);

        event(new UserEmailChanged($this->repository->getById($userId)));
	}
}