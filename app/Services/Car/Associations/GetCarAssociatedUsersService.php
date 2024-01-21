<?php

namespace App\Services\Car\Associations;

use App\Services\Car\AbstractCarService;
use Illuminate\Database\Eloquent\Collection;

class GetCarAssociatedUsersService extends AbstractCarService
{
	public function handle(int $carId): Collection
	{
		return $this->repository->getAssociatedUsers($carId);
	}
}