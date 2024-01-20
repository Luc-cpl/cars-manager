<?php

namespace App\Services\User;

use App\Events\CarDeleted;

class DeleteCarService extends AbstractCarService
{
	public function handle(int $carId, bool $soft = true): void
	{
		$car = $this->repository->getById($carId);
		$this->repository->delete($carId, $soft);
		event(new CarDeleted($car, $soft));
	}
}