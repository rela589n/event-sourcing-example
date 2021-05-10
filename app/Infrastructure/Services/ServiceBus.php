<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Infrastructure\Services\ServiceBus\Concern;
use App\Infrastructure\Services\ServiceBus\Contract;
use JetBrains\PhpStorm\Immutable;

#[Immutable(Immutable::PROTECTED_WRITE_SCOPE)]
abstract class ServiceBus implements Contract
{
    use Concern;
}
