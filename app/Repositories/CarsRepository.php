<?php

namespace App\Repositories;

use App\Models\Car;
use App\Repositories\Interfaces\CarsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class CarsRepository extends AbstractRepository implements CarsRepositoryInterface
{
	public function __construct(
        Car $model
    ) {
        $this->model = $model;
    }

    public function getAssociatedUsers(int $carId): Collection
    {
        /** @var Car|null */
        $car = $this->getById($carId);

        if (!$car) {
            throw new InvalidArgumentException('Car not found');
        }

        return $car->associatedUsers()->get();
    }

    public function associateUser(int $carId, int $userId): void
    {
        /** @var Car|null */
        $car = $this->getById($carId);

        if (!$car) {
            throw new InvalidArgumentException('Car not found');
        }

        if ($car->owner_id === $userId) {
            throw new InvalidArgumentException('User is already the owner of the car');
        }

        $car->associatedUsers()->attach($userId);
    }

    public function disassociateUser(int $carId, int $userId): void
    {
        /** @var Car|null */
        $car = $this->getById($carId);

        if (!$car) {
            throw new InvalidArgumentException('Car not found');
        }

        $car->associatedUsers()->detach($userId);
    }
}