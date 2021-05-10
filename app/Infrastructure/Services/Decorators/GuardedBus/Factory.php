<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\Decorators\GuardedBus;

use App\Infrastructure\Auth\AccessGate;
use App\Infrastructure\Services\ServiceBus;
use JetBrains\PhpStorm\Immutable;

use function implode;

#[Immutable]
final class Factory
{
    public function __construct(
        private ServiceBus\Contract $bus,
        private AccessGate $gate,
    ) {
    }

    public function makeFor(string $className): ServiceBus\Contract
    {
        $traits = [];
        $inherits = 'extends';

        if (ServiceBus\Contract::class === $className) {
            $traits[] = '\\'.ServiceBus\Concern::class;
            $inherits = 'implements';
        }

        $traits = $traits ? 'use '.implode(', ', $traits).';' : '';

        return $this->makeClass($inherits, $className, $traits);
    }

    private function makeClass(string $inherits, string $abstract, string $traits): ServiceBus\Contract
    {
        $bus = $this->bus;
        $gate = $this->gate;

        $php = <<<CODE
\$class = new class(\$bus, \$gate) $inherits \\$abstract {
    $traits
    public function __construct(
         private \$bus,
         private \$gate,
    ) {
    }

    protected function getHandler(object \$command): array
    {
        return \$this->bus->getHandler(\$command);
    }

    protected function runHandler(array \$handler, object \$command)
    {
        \$this->gate->authorize(end(\$handler), \$command);

        return \$this->bus->runHandler(\$handler, \$command);
    }
};
CODE;
        eval($php);

        return $class;
    }
}
