<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Iterator;

abstract class AbstractRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function getById(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function getAll(array $context): Iterator
    {
        return $this->model->where($context)->get();
    }

    public function create(...$params): object
    {
        return $this->model->create($params);
    }

    public function update(...$params): void
    {
        if ($params['id'] ?? false) {
            throw new InvalidArgumentException('Missing id parameter');
        }

        $this->model->find($params['id'])->update($params);
    }

    public function delete(int $id): void
    {
        $this->model->find($id)->delete();
    }
}