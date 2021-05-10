<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Concerns;

use App\Infrastructure\Database\Eloquent\EloquentBuilder;
use Illuminate\Database\Eloquent\Model;

/** @mixin Model */
trait OverridesEloquentBuilder
{
    public function newEloquentBuilder($query): EloquentBuilder
    {
        return new EloquentBuilder($query);
    }
}
