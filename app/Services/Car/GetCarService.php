<?php

namespace App\Services\Car;

use Illuminate\Database\Eloquent\Collection;

/**
 * This class is a service for getting a car by id.
 * It allow us to add logic to the process of getting a car by id if needed.
 */
class GetCarService extends AbstractCarService
{
	public function handle(array $query): Collection
	{
		if ($query['associated_id'] ?? false) {
			$query['where_has'] = $query['where_has'] ?? [];
			$query['where_has'][] = [
				'relation' => 'associatedUsers',
				'field' => 'user_id',
				'value' => $query['associated_id'],
			];
		}

		if ($query['owner_id'] ?? false) {
			$query['where'] = $query['where'] ?? [];
			$query['where'][] = [
				'field' => 'owner_id',
				'value' => $query['owner_id'],
			];
		}

		return $this->repository->getAll($query);
	}
}