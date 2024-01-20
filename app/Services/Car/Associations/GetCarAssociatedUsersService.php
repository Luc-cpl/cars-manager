<?php

namespace App\Services\Car\Associations;

use App\Models\Car;
use App\Services\Car\AbstractCarService;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class GetCarAssociatedUsersService extends AbstractCarService
{
	public function handle(int $carId): Collection
	{
		/** @var Car|null */
		$car = $this->repository->getById($carId);

		if (!$car) {
			throw new InvalidArgumentException('Car not found');
		}

		return $car->associatedUsers()->get();
	}
}