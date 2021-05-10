<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Eloquent;

use App\Infrastructure\Database\AppModel;
use App\Infrastructure\Database\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Builder as BaseEloquentBuilder;

/** @mixin QueryBuilder */
class EloquentBuilder extends BaseEloquentBuilder
{
    /** @psalm-var class-string&AppModel */
    protected static string $holder;

    public static function query(): static
    {
        return static::$holder::query();
    }
}
