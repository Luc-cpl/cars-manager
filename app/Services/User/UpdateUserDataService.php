<?php

namespace App\Services\User;

use App\Events\UserDataUpdated;

class UpdateUserDataService extends AbstractUserService
{
	public function handle(int $userId, string $name): void
	{
		$this->repository->update(
			id: $userId,
			name: $name,
		);

        event(new UserDataUpdated($this->repository->getById($userId)));
	}
}