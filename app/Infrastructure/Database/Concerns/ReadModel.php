<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Concerns;

use App\Infrastructure\Database\AppModel;
use App\Infrastructure\Database\Exceptions\WriteOperationIsNotAllowedForReadModel;
use Illuminate\Database\Eloquent\Builder;

/** @mixin AppModel */
trait ReadModel
{
    protected function insertAndSetId(Builder $query, $attributes)
    {
        throw new WriteOperationIsNotAllowedForReadModel($this);
    }

    protected function performInsert(Builder $query)
    {
        throw new WriteOperationIsNotAllowedForReadModel($this);
    }

    protected function performUpdate(Builder $query)
    {
        throw new WriteOperationIsNotAllowedForReadModel($this);
    }

    protected function performDeleteOnModel(): void
    {
        throw new WriteOperationIsNotAllowedForReadModel($this);
    }

    public function updateOrInsert(array $attributes, array $values = [])
    {
        throw new WriteOperationIsNotAllowedForReadModel($this);
    }

    public function truncate(): void
    {
        throw new WriteOperationIsNotAllowedForReadModel($this);
    }
}
