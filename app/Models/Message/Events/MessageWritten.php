<?php

declare(strict_types=1);

namespace App\Models\Message\Events;

use App\Models\Chat\Doctrine\Chat;
use App\Models\Message\Doctrine\Message;
use App\Models\Message\VO\MessageContent;
use App\Models\User\Doctrine\User;

class MessageWritten extends MessageEvent
{
    private function __construct(
        Message $message,
        private User $user,
        private Chat $chat,
        private MessageContent $content,
    ) {
        parent::__construct($message);
    }

    public static function withData(Message $message, User $user, Chat $chat, MessageContent $content): self
    {
        return new self($message, $user, $chat, $content);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function getContent(): MessageContent
    {
        return $this->content;
    }

    public function NAME(): string
    {
        return 'message_written';
    }
}
