<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\User\AbstractUserService;

/**
 * This class is a service for getting a car by id.
 * It allow us to add logic to the process of getting a car by id if needed.
 */
class GetUserByIdService extends AbstractUserService
{
	public function handle(int $userId): User
	{
		return $this->repository->getById($userId);
	}
}