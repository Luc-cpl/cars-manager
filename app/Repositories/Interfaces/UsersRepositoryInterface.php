<?php

namespace App\Repositories\Interfaces;

interface UsersRepositoryInterface extends BaseRepositoryInterface
{
    public function getByEmail(string $email): ?object;
}