<?php

namespace App\Services\Car\Associations;

use App\Models\Car;
use App\Services\Car\AbstractCarService;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class DisassociateCarWithUserService extends AbstractCarService
{
	public function handle(int $carId, int $userId): Collection
	{
		/** @var Car|null */
		$car = $this->repository->getById($carId);

		if (!$car) {
			throw new InvalidArgumentException('Car not found');
		}

		$car->associatedUsers()->detach($userId);

		return $car->associatedUsers()->get();
	}
}