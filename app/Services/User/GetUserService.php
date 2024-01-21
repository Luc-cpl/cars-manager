<?php

namespace App\Services\User;

use Illuminate\Database\Eloquent\Collection;

class GetUserService extends AbstractUserService
{
	public function handle(array $query): Collection
	{
		return $this->repository->getAll($query);
	}
}