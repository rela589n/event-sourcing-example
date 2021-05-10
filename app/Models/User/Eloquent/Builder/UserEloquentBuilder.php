<?php

declare(strict_types=1);

namespace App\Models\User\Eloquent\Builder;

use App\Infrastructure\Database\Eloquent\EloquentBuilder;
use App\Models\User\Eloquent\User;
use App\Models\User\VO\Login;
use JetBrains\PhpStorm\Immutable;

/** @mixin User */
#[Immutable]
final class UserEloquentBuilder extends EloquentBuilder
{
    protected static string $holder = User::class;

    public function whereLogin(Login $login): self
    {
        return $this->where('email', $login);
    }
}
