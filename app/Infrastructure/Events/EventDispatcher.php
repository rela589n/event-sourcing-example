<?php

declare(strict_types=1);

namespace App\Infrastructure\Events;

use Illuminate\Contracts\Events\Dispatcher;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
interface EventDispatcher extends Dispatcher
{
    /**
     * Dispatch events and call the listeners.
     *
     * @param object[] $events
     */
    public function dispatchMany(array $events): void;
}
