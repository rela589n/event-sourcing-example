<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\ServiceBus;

/**
 * @method runHandler(array $handler, object $command)
 * @method array getHandler(object $command)
 */
interface Contract
{
    public function handle(object $command);
}
