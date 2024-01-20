<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UsersRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UsersRepository extends AbstractRepository implements UsersRepositoryInterface
{
    public function __construct(
        User $model
    ) {
        $this->model = $model;
    }

    private function hashPassword(string $password): string
    {
        return Hash::make($password);
    }

    public function update(...$args): void
    {
        if ($args['password'] ?? false) {
            $args['password'] = $this->hashPassword($args['password']);
        }

        parent::update(...$args);
    }

    public function create(...$args): object
    {
        if ($args['password'] ?? false) {
            $args['password'] = $this->hashPassword($args['password']);
        }

        return parent::create(...$args);
    }

    public function getByEmail(string $email): ?object
    {
        return $this->model->where('email', $email)->first();
    }
}