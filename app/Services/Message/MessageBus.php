<?php

declare(strict_types=1);

namespace App\Services\Message;

use App\Infrastructure\Events\EventDispatcher;
use App\Infrastructure\Services\ServiceBus;
use App\Models\Chat\Doctrine\Chat;
use App\Models\Message\Doctrine\Message;
use App\Models\Message\Eloquent\Builder\MessageEloquentBuilder;
use App\Models\User\Doctrine\User;
use App\Services\Message\Commands\DeleteMessage;
use App\Services\Message\Commands\EditMessage;
use App\Services\Message\Commands\WriteMessage;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use JetBrains\PhpStorm\Immutable;
use LogicException;
use Webmozart\Assert\Assert;

#[Immutable]
final class MessageBus extends ServiceBus
{
    use ServiceBus\PipedBus;

    public function __construct(
        private EntityManager $manager,
        private EventDispatcher $dispatcher,
    ) {
    }

    public function handle(object $command)
    {
        Assert::isInstanceOf($command, MessageCommand::class);

        return parent::handle($command);
    }

    protected function getHandler(object $command): array
    {
        return match (true) {
            $command instanceof WriteMessage => [$this, 'writeMessage'],
            $command instanceof EditMessage => [$this, 'editMessage'],
            $command instanceof DeleteMessage => [$this, 'deleteMessage'],
            default => throw new LogicException('Unknown command: '.$command::class),
        };
    }

    protected function writeMessage(WriteMessage $dto): void
    {
        if (MessageEloquentBuilder::query()
            ->where('uuid', $dto->messageUuid)
            ->exists()) {
            throw new InvalidArgumentException("Message with '$dto->messageUuid' uuid already exists");
        }

        /** @var User $user */
        $user = $this->manager->find(User::class, $dto->userUuid);

        /** @var Chat $chat */
        $chat = $this->manager->find(Chat::class, $dto->chatUuid);

        $message = $user->writeMessage($dto->messageUuid, $chat, $dto->content);

        $this->manager->persist($message);
        $this->manager->flush();

        $this->dispatcher->dispatchMany($message->releaseEvents());
    }

    protected function editMessage(EditMessage $dto): void
    {
        /** @var User $user */
        $user = $this->manager->find(User::class, $dto->userUuid);

        /** @var Message $message */
        $message = $this->manager->find(Message::class, $dto->messageUuid);

        $user->editMessage($message, $dto->newContent);

        $this->manager->flush();

        $this->dispatcher->dispatchMany($message->releaseEvents());
    }

    protected function deleteMessage(DeleteMessage $dto): void
    {
        /** @var User $user */
        $user = $this->manager->find(User::class, $dto->userUuid);

        /** @var Message $message */
        $message = $this->manager->find(Message::class, $dto->messageUuid);

        $user->deleteMessage($message);

        $this->manager->flush();

        $this->dispatcher->dispatchMany($message->releaseEvents());
    }
}
