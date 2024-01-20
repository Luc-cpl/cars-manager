<?php

namespace App\Services\Car;

use Illuminate\Database\Eloquent\Collection;

/**
 * This class is a service for getting a car by id.
 * It allow us to add logic to the process of getting a car by id if needed.
 */
class GetCarService extends AbstractCarService
{
	/**
	 * @todo: Add deleted logic
	 */
	public function handle(array $query): Collection
	{
		$query['limit'] = $query['limit'] ?? 10;
		return $this->repository->getAll($query);
	}
}