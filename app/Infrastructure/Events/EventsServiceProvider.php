<?php

declare(strict_types=1);

namespace App\Infrastructure\Events;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class EventsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->extend(
            'events',
            function (Dispatcher $service, $app) {
                return new EventDispatcher\Impl($service);
            },
        );

        $this->app->alias('events', EventDispatcher::class);
        $this->app->alias('events', EventDispatcher\Impl::class);
    }
}
