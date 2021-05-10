<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\ServiceBus;

use App\Infrastructure\Services\Pipeline\PipedMethod;
use JetBrains\PhpStorm\Immutable;

use function array_merge;
use function end;

#[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
trait PipedBus
{
    protected array $pipes = [];

    use Concern {
        runHandler as private doRunHandler;
    }

    protected function runHandler(array $handler, object $command)
    {
        $pipes = array_merge($this->pipes, [fn($c) => $this->doRunHandler($handler, $c)]);
        $pipeline = new PipedMethod(end($handler), $pipes);

        return $pipeline->handle($command);
    }

    public function addPipe(array|callable $pipe): self
    {
        $this->pipes [] = $pipe;

        return $this;
    }
}
