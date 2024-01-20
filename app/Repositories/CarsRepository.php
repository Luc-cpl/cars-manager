<?php

namespace App\Repositories;

use App\Models\Car;
use App\Repositories\Interfaces\CarsRepositoryInterface;

class CarsRepository extends AbstractRepository implements CarsRepositoryInterface
{
	public function __construct(
        Car $model
    ) {
        $this->model = $model;
    }
}