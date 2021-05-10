<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\Pipeline;

use BadMethodCallException;
use JetBrains\PhpStorm\Immutable;

#[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
final class PipedMethod
{
    private int $call = 0;

    public function __construct(private string $methodName, private array $pipes)
    {
    }

    public function handle(object $command)
    {
        return $this->pipes[$this->call++]($command, [$this, $this->methodName]);
    }

    public function __call(string $name, array $arguments)
    {
        if ($name !== $this->methodName) {
            throw new BadMethodCallException("Expected to call $this->methodName method");
        }

        return $this->handle(...$arguments);
    }
}
