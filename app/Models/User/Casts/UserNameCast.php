<?php

declare(strict_types=1);

namespace App\Models\User\Casts;

use App\Models\User\Doctrine\User as DoctrineUser;
use App\Models\User\Eloquent\User as EloquentUser;
use App\Models\User\VO\UserName;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes as CastsAttributesEloquent;
use Rela589n\DoctrineEventSourcing\Serializer\Separate\Castable\Contract\CastsAttributes;
use Webmozart\Assert\Assert;

class UserNameCast implements CastsAttributesEloquent, CastsAttributes
{
    public function get($model, $key, $value, $attributes): UserName
    {
        Assert::isInstanceOfAny($model, [DoctrineUser::class, EloquentUser::class]);

        return UserName::fromString($attributes['name']);
    }

    public function set($model, $key, $value, $attributes): array
    {
        Assert::isInstanceOfAny($model, [DoctrineUser::class, EloquentUser::class]);
        Assert::isInstanceOf($value, UserName::class);

        return [
            'name' => (string)$value,
        ];
    }
}
