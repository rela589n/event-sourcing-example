<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Concerns;

use App\Infrastructure\Database\Eloquent\EloquentCollection;
use Illuminate\Database\Eloquent\Model;

/** @mixin Model */
trait OverridesEloquentCollection
{
    public function newCollection(array $models = []): EloquentCollection
    {
        return new EloquentCollection($models);
    }
}
