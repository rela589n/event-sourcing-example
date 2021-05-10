<?php

declare(strict_types=1);


namespace App\Models\User\VO;

use App\Models\User\Casts\UserNameCast;
use Illuminate\Contracts\Database\Eloquent\Castable as EloquentCastable;
use Rela589n\DoctrineEventSourcing\Serializer\Separate\Castable\Contract\Castable;
use Webmozart\Assert\Assert;

final class UserName implements EloquentCastable, Castable
{
    private string $name;

    public function __construct(string $name)
    {
        Assert::lengthBetween($name, 3, 64);

        $this->name = $name;
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public static function castUsing(array $arguments)
    {
        return new UserNameCast();
    }
}
