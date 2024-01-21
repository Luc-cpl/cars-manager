<?php

namespace App\Services\User;

use App\Events\UserDeleted;

class DeleteUserService extends AbstractUserService
{
	public function handle(int $userId, bool $soft = true): void
	{
		$user = $this->repository->getById($userId);
		$this->repository->delete($userId, $soft);
		event(new UserDeleted($user, $soft));
	}
}