<?php

namespace App\Services\Car;

use App\Events\CarUpdated;
use App\Models\Car;

class UpdateCarService extends AbstractCarService
{
	public function handle(int $carId, string $name): Car
	{
		$this->repository->update(
			id: $carId,
			name: $name,
		);

		$car = $this->repository->getById($carId);

		event(new CarUpdated($car));

		return $car;
	}
}