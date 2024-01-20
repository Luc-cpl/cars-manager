<?php

namespace App\Services\Car;

use App\Models\Car;

/**
 * This class is a service for getting a car by id.
 * It allow us to add logic to the process of getting a car by id if needed.
 */
class GetCarByIdService extends AbstractCarService
{
	public function handle(int $carId): Car
	{
		return $this->repository->getById($carId);
	}
}