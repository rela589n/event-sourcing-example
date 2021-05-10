<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\ServiceBus;

/** @mixin Contract */
trait Concern
{
    public function handle(object $command)
    {
        $handler = $this->getHandler($command);

        return $this->runHandler($handler, $command);
    }

    protected function runHandler(array $handler, object $command)
    {
        return $handler($command);
    }

    abstract protected function getHandler(object $command): array;
}
