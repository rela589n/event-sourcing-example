<?php

declare(strict_types=1);

namespace App\Models\Chat\Events;

use App\Models\Chat\Doctrine\Chat;
use App\Models\Chat\VO\ChatName;
use App\Models\User\Doctrine\User;
use Doctrine\DBAL\Types\Types;
use Rela589n\DoctrineEventSourcing\Event\Annotations\SerializeAs;

class ChatCreated extends ChatEvent
{
    #[SerializeAs(type: Types::BOOLEAN, name: 'custom')]
    private int $someArbitraryValue;

    #[SerializeAs(name: 'chat_name')]
    private ChatName $chatName;

    #[SerializeAs(name: 'user')]
    private User $creator;

    public function __construct(Chat $chat, User $creator, ChatName $name)
    {
        parent::__construct($chat);
        $this->chatName = $name;
        $this->someArbitraryValue = 324234;
        $this->creator = $creator;
    }

    public static function with(Chat $chat, User $creator, ChatName $name): self
    {
        return new self($chat, $creator, $name);
    }

    public function getChatName(): ChatName
    {
        return $this->chatName;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function NAME(): string
    {
        return 'chat_created';
    }

    public function getEntity(): Chat
    {
        return $this->entity;
    }
}
