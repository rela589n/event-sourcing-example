<?php

declare(strict_types=1);

namespace App\Models\Chat\Doctrine;

use App\Models\Chat\Events\ChatCreated;
use App\Models\Chat\Events\ChatEvent;
use App\Models\Chat\Events\UserJoinedChat;
use App\Models\Chat\Events\UserKickedOutFromChat;
use App\Models\Chat\Events\UserLeftChat;
use App\Models\Chat\VO\ChatName;
use App\Models\Message\Doctrine\Message;
use App\Models\User\Doctrine\User;
use Carbon\CarbonInterface as Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface as Uuid;
use Rela589n\DoctrineEventSourcing\Entity\AggregateRoot;
use Rela589n\DoctrineEventSourcing\Event\Exceptions\UnexpectedAggregateChangeEvent;
use RuntimeException;

class Chat implements AggregateRoot
{
    public const MAX_CHATS_PER_DAY = 5;

    private Uuid $uuid;

    private ChatName $name;

    private Carbon $createdAt;

    private Carbon $updatedAt;

    private User $creator;

    /** @var Collection<User> */
    private Collection $users;

    /** @var Collection<Message> */
    private Collection $messages;

    /** @var Collection<ChatEvent> */
    private Collection $recordedEvents;

    /** @var ChatEvent[] */
    private array $newlyRecordedEvents = [];

    private function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
        $this->users = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->recordedEvents = new ArrayCollection();
    }

    public static function create(User $creator, Uuid $uuid, ChatName $name): self
    {
        $chat = new self($uuid);
        $chat->recordThat(ChatCreated::with($chat, $creator, $name));
        $chat->acceptUser($creator);
        return $chat;
    }

    public function addMessage(Message $message): void
    {
        $this->messages->add($message);
    }

    public function acceptUser(User $user): void
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->recordThat(UserJoinedChat::with($user, $this));
    }

    public function kickUserOut(User $user): void
    {
        if (!$this->users->contains($user)) {
            return;
        }

        $this->recordThat(UserKickedOutFromChat::with($user, $this));
    }

    public function letUserGo(User $user): void
    {
        if (!$this->users->contains($user)) {
            return;
        }

        $this->recordThat(UserLeftChat::with($user, $this));
    }

    private function recordThat(ChatEvent $event): void
    {
        switch (true) {
            case $event instanceof ChatCreated:
                $this->createdAt = \Carbon\Carbon::instance($event->getTimestamp());
                $this->name = $event->getChatName();
                $this->creator = $event->getCreator();
                if ($this->creator->chatsCreatedToday()->count() >= self::MAX_CHATS_PER_DAY) {
                    throw new RuntimeException('You have already created '.self::MAX_CHATS_PER_DAY.' chats today.');
                }
                $this->creator->addCreatedChat($this);
                break;
            case $event instanceof UserJoinedChat:
                $user = $event->getUser();
                $this->users->add($user);
                $user->joinChat($this);
                break;
            case $event instanceof UserKickedOutFromChat:
                $user = $event->getUser();
                $this->users->removeElement($user);
                $user->getOutOfChat($this);
                break;
            case $event instanceof UserLeftChat:
                $user = $event->getUser();
                $this->users->removeElement($user);
                $user->leaveChat($this);
                break;
            default:
                throw new UnexpectedAggregateChangeEvent($event);
        }

        $this->updatedAt = \Carbon\Carbon::instance($event->getTimestamp());
        $this->newlyRecordedEvents[] = $event;
        $this->recordedEvents->add($event);
    }

    public function releaseEvents(): array
    {
        $events = $this->newlyRecordedEvents;
        $this->newlyRecordedEvents = [];
        return $events;
    }

    public function hasUser(User $user): bool
    {
        return $this->users->contains($user);
    }

    public function createdAfter(Carbon $date): bool
    {
        return $this->createdAt->isAfter($date);
    }

    public function getPrimary(): Uuid
    {
        return $this->uuid;
    }

    public static function getPrimaryName(): string
    {
        return 'uuid';
    }
}
