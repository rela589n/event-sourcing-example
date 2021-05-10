<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Infrastructure\Services\Decorators\GuardedBus;
use Illuminate\Support\ServiceProvider;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class ServiceBusProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            GuardedBus::class,
            fn($a, $args) => $this->app->make(
                GuardedBus\Factory::class,
                $args,
            )->makeFor(
                $args['className'],
            ),
        );
    }
}
