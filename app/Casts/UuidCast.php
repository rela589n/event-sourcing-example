<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Webmozart\Assert\Assert;

final class UuidCast implements CastsAttributes
{
    public function __construct(private string $column) { }

    public function get($model, string $key, $value, array $attributes): UuidInterface
    {
        return Uuid::fromString($attributes[$this->column]);
    }

    public function set($model, string $key, $value, array $attributes): array
    {
        Assert::isInstanceOf($value, UuidInterface::class);

        return [
            $this->column => (string)$value,
        ];
    }
}
