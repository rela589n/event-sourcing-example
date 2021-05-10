<?php

declare(strict_types=1);

namespace App\Infrastructure\Events\EventDispatcher;

use App\Infrastructure\Events\EventDispatcher;
use Illuminate\Contracts\Events\Dispatcher;
use JetBrains\PhpStorm\Immutable;

use function array_map;

#[Immutable]
final class Impl implements EventDispatcher
{
    public function __construct(private Dispatcher $decorated) { }

    public function listen($events, $listener = null)
    {
        return $this->decorated->listen($events, $listener);
    }

    public function hasListeners($eventName)
    {
        return $this->decorated->hasListeners($eventName);
    }

    public function subscribe($subscriber)
    {
        return $this->decorated->subscribe($subscriber);
    }

    public function until($event, $payload = [])
    {
        return $this->decorated->until($event, $payload);
    }

    public function dispatch($event, $payload = [], $halt = false)
    {
        return $this->decorated->dispatch($event, $payload, $halt);
    }

    public function push($event, $payload = [])
    {
        return $this->decorated->push($event, $payload);
    }

    public function flush($event)
    {
        return $this->decorated->flush($event);
    }

    public function forget($event)
    {
        return $this->decorated->forget($event);
    }

    public function forgetPushed()
    {
        return $this->decorated->forgetPushed();
    }

    public function dispatchMany(array $events): void
    {
        array_map([$this->decorated, 'dispatch'], $events);
    }
}
