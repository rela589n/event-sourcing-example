<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\Pipes;

use App\Infrastructure\Auth\AccessGate;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class Guarded
{
    public function __construct(private AccessGate $gate) { }

    public function handle(object $command, array $handle)
    {
        $this->gate->authorize(end($handle), $command);

        return $handle($command);
    }
}
