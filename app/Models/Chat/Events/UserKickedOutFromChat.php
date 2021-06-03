<?php

declare(strict_types=1);

namespace App\Models\Chat\Events;

use App\Models\Chat\Doctrine\Chat;
use App\Models\User\Doctrine\User;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
class UserKickedOutFromChat extends ChatEvent
{
    public function __construct(private User $user, Chat $chat)
    {
        parent::__construct($chat);
    }

    public static function with(User $user, Chat $chat): self
    {
        return new self($user, $chat);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getChat(): Chat
    {
        return $this->entity;
    }

    public static function NAME(): string
    {
        return 'user_kicked_out_from_chat';
    }
}
