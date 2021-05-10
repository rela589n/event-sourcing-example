<?php

declare(strict_types=1);

namespace App\Models\User\Casts;

use App\Models\User\Doctrine\User as DoctrineUser;
use App\Models\User\Eloquent\User as EloquentUser;
use App\Models\User\VO\Login;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes as CastsAttributesEloquent;
use Rela589n\DoctrineEventSourcing\Serializer\Separate\Castable\Contract\CastsAttributes;
use Webmozart\Assert\Assert;

class UserLoginCast implements CastsAttributesEloquent, CastsAttributes
{
    public function get($model, $key, $value, $attributes): Login
    {
        Assert::isInstanceOfAny($model, [DoctrineUser::class, EloquentUser::class]);

        return Login::fromString($attributes['email']);
    }

    public function set($model, $key, $value, $attributes): array
    {
        Assert::isInstanceOfAny($model, [DoctrineUser::class, EloquentUser::class]);
        Assert::isInstanceOf($value, Login::class);

        return [
            'email' => (string)$value,
        ];
    }
}
