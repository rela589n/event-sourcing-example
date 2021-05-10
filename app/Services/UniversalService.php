<?php

declare(strict_types=1);

namespace App\Services;

use App\Infrastructure\Services\ServiceBus;
use App\Services\Chat\ChatBus;
use App\Services\Chat\ChatCommand;
use App\Services\Message\MessageBus;
use App\Services\Message\MessageCommand;
use App\Services\User\UserBus;
use App\Services\User\UserCommand;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class UniversalService extends ServiceBus
{
    public function __construct(
        private ChatBus $chatBus,
        private MessageBus $messageBus,
        private UserBus $userBus,
    ) {
    }

    protected function getHandler(object $command): array
    {
        return match (true) {
            $command instanceof ChatCommand => [$this->chatBus, 'handle'],
            $command instanceof MessageCommand => [$this->messageBus, 'handle'],
            $command instanceof UserCommand => [$this->userBus, 'handle'],
        };
    }
}
