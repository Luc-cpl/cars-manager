<?php

namespace App\Services\User;

use App\Events\CarRestored;

class RestoreCarService extends AbstractCarService
{
	public function handle(int $carId): void
	{
		$car = $this->repository->getById($carId);
		$this->repository->restore($carId);
		event(new CarRestored($car));
	}
}