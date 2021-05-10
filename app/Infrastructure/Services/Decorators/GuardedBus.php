<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\Decorators;

use App\Infrastructure\Auth\AccessGate;
use App\Infrastructure\Services\Decorators\GuardedBus\Factory;
use App\Infrastructure\Services\ServiceBus;
use JetBrains\PhpStorm\Immutable;

use function end;

/**
 * @see Factory
 * @note this code is not actually executed, but code located in factory
 */
#[Immutable]
final class GuardedBus implements ServiceBus\Contract
{
    use ServiceBus\Concern;

    public function __construct(
        private ServiceBus\Contract $bus,
        private AccessGate $gate,
    ) {
    }

    protected function getHandler(object $command): array
    {
        return $this->bus->getHandler($command);
    }

    protected function runHandler(array $handler, object $command)
    {
        $this->gate->authorize(end($handler), $command);

        return $this->bus->runHandler($handler, $command);
    }
}
