<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\AccessGate;

use App\Infrastructure\Auth\AccessGate;
use Illuminate\Auth\Access\Gate;
use JetBrains\PhpStorm\Immutable;
use ReflectionClass;

#[Immutable]
final class Impl extends Gate implements AccessGate
{
    public function __construct(Gate $decorated)
    {
        parent::__construct(
            $decorated->container,
            $decorated->userResolver,
        );

        $this->pretendToBeLaravelGate($decorated);
    }

    public function orGuessPolicyNamesUsing(callable $callback): self
    {
        if (null === $this->guessPolicyNamesUsingCallback) {
            return $this->guessPolicyNamesUsing($callback);
        }

        return $this->guessPolicyNamesUsing(
            fn($class) => ($this->guessPolicyNamesUsingCallback)($class) ?: $callback($class),
        );
    }

    private function pretendToBeLaravelGate(Gate $decorated): void
    {
        $reflectionClass = new ReflectionClass($decorated);

        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);

            if ($property->isStatic()) {
                $reflectionClass->setStaticPropertyValue($property->name, $property->getValue());
                continue;
            }

            $property->setValue($this, $property->getValue($decorated));
        }
    }
}
