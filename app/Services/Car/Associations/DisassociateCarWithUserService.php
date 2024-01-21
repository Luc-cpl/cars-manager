<?php

namespace App\Services\Car\Associations;

use App\Services\Car\AbstractCarService;

class DisassociateCarWithUserService extends AbstractCarService
{
	public function handle(int $carId, int $userId): void
	{
		$this->repository->disassociateUser($carId, $userId);
	}
}