<?php

namespace App\Services\Car\Associations;

use App\Services\Car\AbstractCarService;

class AssociateCarWithUserService extends AbstractCarService
{
	public function handle(int $carId, int $userId): void
	{
		$this->repository->associateUser($carId, $userId);
	}
}