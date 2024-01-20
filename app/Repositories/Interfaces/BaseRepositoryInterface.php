<?php

namespace App\Repositories\Interfaces;

use InvalidArgumentException;
use Iterator;

interface BaseRepositoryInterface
{
    public function getById(int $id): ?object;

    public function getAll(array $context): Iterator;

    public function create(...$params): object;

    /**
     * @throws InvalidArgumentException
     */
    public function update(...$params): void;

    public function delete(int $id): void;
}