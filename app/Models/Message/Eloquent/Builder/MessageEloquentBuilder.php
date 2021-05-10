<?php

declare(strict_types=1);

namespace App\Models\Message\Eloquent\Builder;

use App\Infrastructure\Database\Eloquent\EloquentBuilder;
use App\Models\Message\Eloquent\Message;
use JetBrains\PhpStorm\Immutable;

/** @mixin Message */
#[Immutable]
final class MessageEloquentBuilder extends EloquentBuilder
{
    protected static string $holder = Message::class;
}
