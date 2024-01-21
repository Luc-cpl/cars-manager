<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UsersRepositoryInterface;

class UsersRepository extends AbstractRepository implements UsersRepositoryInterface
{
    public function __construct(
        User $model
    ) {
        $this->model = $model;
    }

    public function getByEmail(string $email): ?object
    {
        return $this->model->where('email', $email)->first();
    }
}