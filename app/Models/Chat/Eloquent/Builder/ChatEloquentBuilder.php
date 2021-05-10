<?php

declare(strict_types=1);

namespace App\Models\Chat\Eloquent\Builder;

use App\Infrastructure\Database\Eloquent\EloquentBuilder;
use App\Models\Chat\Eloquent\Chat;
use JetBrains\PhpStorm\Immutable;

/** @mixin Chat */
#[Immutable]
final class ChatEloquentBuilder extends EloquentBuilder
{
    protected static string $holder = Chat::class;
}
