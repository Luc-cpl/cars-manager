<?php

namespace App\Services\User;

use App\Events\UserRestored;

class RestoreUserService extends AbstractUserService
{
	public function handle(int $userId): void
	{
		$user = $this->repository->getById($userId);
		$this->repository->restore($userId);
		event(new UserRestored($user));
	}
}