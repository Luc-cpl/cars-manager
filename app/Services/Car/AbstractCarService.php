<?php

namespace App\Services\Car;

use App\Repositories\Interfaces\CarsRepositoryInterface;

abstract class AbstractCarService
{
	public function __construct(
		protected CarsRepositoryInterface $repository,
	) {
	}
}