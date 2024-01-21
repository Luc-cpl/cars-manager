<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface CarsRepositoryInterface extends BaseRepositoryInterface
{
	public function getAssociatedUsers(int $carId): Collection;
	public function associateUser(int $carId, int $userId): void;
	public function disassociateUser(int $carId, int $userId): void;
}