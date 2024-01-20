<?php

namespace App\Services\User;

use App\Repositories\Interfaces\CarsRepositoryInterface;

abstract class AbstractCarService
{
	public function __construct(
		protected CarsRepositoryInterface $repository,
	) {
	}
}