<?php

declare(strict_types=1);

namespace App\Models\Message\Events;

use App\Models\Message\Doctrine\Message;
use App\Models\User\Doctrine\User;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
class MessageWasDeleted extends MessageEvent
{
    public function __construct(
        Message $message,
        private User $user,
    ) {
        parent::__construct($message);
    }

    public static function by(User $user, Message $message): self
    {
        return new self($message, $user);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getMessage(): Message
    {
        return $this->entity;
    }

    public function NAME(): string
    {
        return 'message_deleted';
    }
}
