<?php

declare(strict_types=1);

namespace App\Models\User\Doctrine;

use App\Models\Chat\Doctrine\Chat;
use App\Models\Chat\VO\ChatName;
use App\Models\Message\Doctrine\Message;
use App\Models\Message\VO\MessageContent;
use App\Models\User\Events\UserChangedLogin;
use App\Models\User\Events\UserChangedPassword;
use App\Models\User\Events\UserEvent;
use App\Models\User\Events\UserRegistered;
use App\Models\User\Events\UserSetName;
use App\Models\User\VO\Login;
use App\Models\User\VO\Password;
use App\Models\User\VO\UserName;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface as Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\UuidInterface as Uuid;
use Rela589n\DoctrineEventSourcing\Entity\AggregateRoot;
use Rela589n\DoctrineEventSourcing\Event\Exceptions\UnexpectedAggregateChangeEvent;

class User implements AggregateRoot
{
    private Uuid $uuid;

    private Login $login;

    private Password $password;

    private UserName $name;

    private Carbon $createdAt;

    private Carbon $updatedAt;

    /** @var Collection<Chat> */
    private Collection $chats;

    /** @var Collection<Chat> */
    private Collection $createdChats;

    /** @var Collection<UserEvent> */
    private Collection $recordedEvents;

    /** @var UserEvent[] */
    private array $newlyRecordedEvents = [];

    private function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
        $this->chats = new ArrayCollection();
        $this->createdChats = new ArrayCollection();
        $this->recordedEvents = new ArrayCollection();
    }

    public static function register(Uuid $uuid, Login $login, Password $password, UserName $name): self
    {
        $user = new self($uuid);

        $user->recordThat(UserRegistered::withCredentials($user, $login, $password));
        $user->recordThat(UserSetName::with($user, $name));

        return $user;
    }

    public function changeLogin(Login $newLogin): void
    {
        $this->recordThat(UserChangedLogin::fromInto($this, $this->login, $newLogin));
    }

    public function changePassword(Password $newPassword): void
    {
        $this->recordThat(UserChangedPassword::with($this, $newPassword));
    }

    public function createChat(UuidInterface $chatUuid, ChatName $chatName): Chat
    {
        return Chat::create($this, $chatUuid, $chatName);
    }

    public function chatsCreatedToday(): Collection
    {
        $today = CarbonImmutable::today();

        return $this->createdChats->filter(static fn(Chat $c) => $c->createdAfter($today));
    }

    public function addCreatedChat(Chat $chat): void
    {
        if (!$this->createdChats->contains($chat)) {
            $this->createdChats->add($chat);
        }
    }

    public function joinChat(Chat $chat): void
    {
        if ($this->chats->contains($chat)) {
            return;
        }

        $this->chats->add($chat);
        $chat->acceptUser($this);
    }

    public function getOutOfChat(Chat $chat): void
    {
        if (!$this->chats->contains($chat)) {
            return;
        }

        $this->chats->removeElement($chat);
        $chat->kickUserOut($this);
    }

    public function leaveChat(Chat $chat): void
    {
        if (!$this->chats->contains($chat)) {
            return;
        }

        $this->chats->removeElement($chat);
        $chat->letUserGo($this);
    }

    public function writeMessage(Uuid $uuid, Chat $chat, MessageContent $content): Message
    {
        return Message::write($uuid, $this, $chat, $content);
    }

    public function editMessage(Message $message, MessageContent $newContent): void
    {
        $message->edit($this, $newContent);
    }

    public function deleteMessage(Message $message): void
    {
        $message->delete($this);
    }

    private function recordThat(UserEvent $event): void
    {
        switch (true) {
            case $event instanceof UserRegistered:
                $this->createdAt = \Carbon\Carbon::instance($event->getTimestamp());
                $this->login = $event->getLogin();
                $this->password = $event->getPassword();
                break;
            case $event instanceof UserSetName:
                $this->name = $event->getUserName();
                break;
            case $event instanceof UserChangedLogin:
                $this->login = $event->getNewLogin();
                break;
            case $event instanceof UserChangedPassword:
                $this->password = $event->getNewPassword();
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

    public static function getPrimaryName(): string
    {
        return 'uuid';
    }

    public function getPrimary(): Uuid
    {
        return $this->uuid;
    }
}
