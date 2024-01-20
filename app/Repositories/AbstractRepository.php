<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

abstract class AbstractRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function getById(int $id): ?object
    {
        return $this->model->withTrashed()->find($id);
    }

    public function getAll(array $query): Collection
    {
        $limit = $query['limit'] ?? 15;
        $page = $query['page'] ?? 1;
        $where = $query['where'] ?? [];
        $whereHas = $query['where_has'] ?? [];

        $queryBuilder = $this->model->query();

        $queryBuilder->limit($limit);
        $queryBuilder->offset(($page - 1) * $limit);

        if ($query['deleted'] ?? false) {
            $queryBuilder->onlyTrashed();
        }

        foreach ($where as $whereItem) {
            $operator = $whereItem['operator'] ?? '=';
            $value = $whereItem['value'] ?? null;

            if (!in_array($whereItem['field'], $this->model->getFillable())) {
                throw new InvalidArgumentException("Invalid field {$whereItem['field']} for {$this->model->getTable()}");
            }

            $queryBuilder->where($whereItem['field'], $operator, $value);
        }

        foreach ($whereHas as $whereHasItem) {
            $queryBuilder->whereHas($whereHasItem['relation'], function (Builder $subQuery) use ($whereHasItem) {
                $subQuery->where($whereHasItem['field'], $whereHasItem['value']);
            });
        }

        return $queryBuilder->get();
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
        $entity = $this->getById($id);

        if (!$entity) {
            throw new InvalidArgumentException('Entity not found');
        }

        if ($soft) {
            $entity->delete();
        } else {
            $entity->forceDelete();
        }
    }

    public function restore(int $id): void
    {
        $this->model->withTrashed()->find($id)->restore();
    }
}