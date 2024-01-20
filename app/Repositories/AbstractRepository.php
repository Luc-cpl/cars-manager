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

    public function getAll(array $query): Iterator
    {
        return $this->model->where($query)->get();
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

    public function delete(int $id, bool $soft = true): void
    {
        if ($soft) {
            $this->model->find($id)->delete();
        } else {
            $this->model->find($id)->forceDelete();
        }
    }

    public function restore(int $id): void
    {
        $this->model->withTrashed()->find($id)->restore();
    }
}