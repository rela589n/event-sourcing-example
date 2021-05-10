<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

use JetBrains\PhpStorm\Immutable;

/** @mixin \Illuminate\Auth\Access\Gate */
#[Immutable]
interface AccessGate extends \Illuminate\Contracts\Auth\Access\Gate
{
    /**
     * Determine if all of the given abilities should be denied for the current user.
     *
     * @param iterable|string $abilities
     * @param array|mixed $arguments
     *
     * @return bool
     */
    public function none($abilities, $arguments = []);

    /**
     * Specify a callback to be used to guess policy names.
     *
     * @param callable $callback
     *
     * @return \Illuminate\Auth\Access\Gate
     */
    public function guessPolicyNamesUsing(callable $callback);

    public function orGuessPolicyNamesUsing(callable $callback);

    /**
     * Build a policy class instance of the given type.
     *
     * @param object|string $class
     *
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function resolvePolicy($class);
}
