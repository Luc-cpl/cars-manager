<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

interface BaseRepositoryInterface
{
    public function getById(int $id): ?object;

    public function getAll(array $query): Collection;

    public function create(...$params): object;

    /**
     * @throws InvalidArgumentException
     */
    public function update(...$params): void;

    public function delete(int $id, bool $soft = true): void;

    public function restore(int $id): void;
}