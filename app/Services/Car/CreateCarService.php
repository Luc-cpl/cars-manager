<?php

namespace App\Services\User;

use App\Events\CarCreated;
use App\Models\Car;
use App\Repositories\Interfaces\CarsRepositoryInterface;
use App\Repositories\Interfaces\UsersRepositoryInterface;
use InvalidArgumentException;

class CreateCarService extends AbstractCarService
{
	public function __construct(
		private CarsRepositoryInterface $repository,
		private UsersRepositoryInterface $usersRepository,
	) {
	}

	public function handle(int $ownerId): Car
	{
		$owner = $this->usersRepository->getById($ownerId);

		if (!$owner) {
			throw new InvalidArgumentException('Owner not found');
		}

		$car = $this->repository->create(
			owner_id: $ownerId
		);

		event(new CarCreated($car));

		return $car;
	}
}