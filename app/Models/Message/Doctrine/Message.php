<?php

declare(strict_types=1);

namespace App\Models\Message\Doctrine;

use App\Models\Chat\Doctrine\Chat;
use App\Models\Message\Events\MessageEvent;
use App\Models\Message\Events\MessageWasDeleted;
use App\Models\Message\Events\MessageWasEdited;
use App\Models\Message\Events\MessageWritten;
use App\Models\Message\VO\MessageContent;
use App\Models\Message\VO\MessageStatus;
use App\Models\User\Doctrine\User;
use Carbon\CarbonInterface as Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface as Uuid;
use Rela589n\DoctrineEventSourcing\Entity\AggregateRoot;
use Rela589n\DoctrineEventSourcing\Event\Exceptions\UnexpectedAggregateChangeEvent;
use RuntimeException;

class Message implements AggregateRoot
{
    public const MAX_EDITS = 5;

    private Uuid $uuid;

    private MessageContent $content;

    private MessageStatus $status;

    private Carbon $createdAt;

    private Carbon $updatedAt;

    private User $user;

    private Chat $chat;

    /** @var Collection<MessageEvent> */
    private Collection $recordedEvents;

    /** @var MessageEvent[] */
    private array $newlyRecordedEvents = [];

    private function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
        $this->status = MessageStatus::NEW();
        $this->recordedEvents = new ArrayCollection();
    }

    public static function write(Uuid $uuid, User $user, Chat $chat, MessageContent $content): self
    {
        $message = new self($uuid);

        $message->recordThat(MessageWritten::withData($message, $user, $chat, $content));

        return $message;
    }

    public function edit(User $editor, MessageContent $newContent): void
    {
        $this->recordThat(MessageWasEdited::with($this, $editor, $this->content, $newContent));
    }

    public function delete(User $deleter): void
    {
        $this->recordThat(MessageWasDeleted::by($deleter, $this));
    }

    private function recordThat(MessageEvent $event): void
    {
        switch (true) {
            case $event instanceof MessageWritten:
                $this->createdAt = \Carbon\Carbon::instance($event->getTimestamp());
                $this->user = $event->getUser();
                $this->content = $event->getContent();
                $this->chat = $event->getChat();
                if (!$this->chat->hasUser($this->user)) {
                    // todo throw authorization error instead
                    throw new RuntimeException('User can write messages only in joined chats.');
                }
                $this->chat->addMessage($this);
                break;

            case $event instanceof MessageWasEdited:
                if (!$this->wasWrittenBy($event->getEditor())) {
                    // todo throw authorization error instead
                    throw new RuntimeException('Only message owner can edit message');
                }

                if ($this->editsCount() > self::MAX_EDITS) {
                    throw new RuntimeException('Can not edit message more than '.self::MAX_EDITS.' times');
                }

                $this->content = $event->getNewContent();
                $this->status = $this->status->transitionInto(MessageStatus::EDITED());
                break;

            case $event instanceof MessageWasDeleted:
                if (!$this->wasWrittenBy($event->getUser())) {
                    // todo throw authorization error instead
                    throw new RuntimeException('Only message owner can edit message');
                }
                // when setters are supported
                // $this->status = MessageStatus::DELETED();
                $this->status = $this->status->transitionInto(MessageStatus::DELETED());
                break;
            default:
                throw new UnexpectedAggregateChangeEvent($event);
        }

        $this->updatedAt = \Carbon\Carbon::instance($event->getTimestamp());
        $this->newlyRecordedEvents[] = $event;
        $this->recordedEvents->add($event);
    }

    private function wasWrittenBy(User $user): bool
    {
        return $user === $this->user;
    }

    private function editsCount(): int
    {
        return $this->recordedEvents
            ->filter(static fn(MessageEvent $e) => $e instanceof MessageWasEdited)
            ->count();
    }

    public function releaseEvents(): array
    {
        $events = $this->newlyRecordedEvents;
        $this->newlyRecordedEvents = [];
        return $events;
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
