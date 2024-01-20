<?php

namespace App\Services\Car\Associations;

use App\Models\Car;
use App\Services\Car\AbstractCarService;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class AssociateCarWithUserService extends AbstractCarService
{
	public function handle(int $carId, int $userId): Collection
	{
		/** @var Car|null */
		$car = $this->repository->getById($carId);

		if (!$car) {
			throw new InvalidArgumentException('Car not found');
		}

		if ($car->owner_id === $userId) {
			throw new InvalidArgumentException('User is already the owner of the car');
		}

		$car->associatedUsers()->attach($userId);

		return $car->associatedUsers()->get();
	}
}