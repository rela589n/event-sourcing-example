<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->alias(Gate::class, AccessGate::class);

        $this->app->extend(
            Gate::class,
            fn($s) => $this->app->make(AccessGate\Impl::class, ['decorated' => $s]),
        );
    }
}
