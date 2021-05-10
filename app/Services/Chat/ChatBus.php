<?php

declare(strict_types=1);

namespace App\Services\Chat;

use App\Infrastructure\Events\EventDispatcher;
use App\Infrastructure\Services\ServiceBus;
use App\Models\Chat\Doctrine\Chat;
use App\Models\Chat\Eloquent\Builder\ChatEloquentBuilder;
use App\Models\User\Doctrine\User;
use App\Services\Chat\Commands\CreateChat;
use App\Services\Chat\Commands\JoinChat;
use App\Services\Chat\Commands\KickUserOutFromChat;
use App\Services\Chat\Commands\LeaveChat;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use JetBrains\PhpStorm\Immutable;
use LogicException;
use Webmozart\Assert\Assert;

#[Immutable]
final class ChatBus extends ServiceBus
{
    use ServiceBus\PipedBus;

    public function __construct(
        private EntityManager $manager,
        private EventDispatcher $dispatcher,
    ) {
    }

    public function handle(object $command)
    {
        Assert::isInstanceOf($command, ChatCommand::class);

        return parent::handle($command);
    }

    protected function getHandler(object $command): array
    {
        return match (true) {
            $command instanceof CreateChat => [$this, 'createChat'],
            $command instanceof JoinChat => [$this, 'joinChat'],
            $command instanceof KickUserOutFromChat => [$this, 'kickOutFromChat'],
            $command instanceof LeaveChat => [$this, 'leaveChat'],
            default => throw new LogicException('Unknown command: '.$command::class),
        };
    }

    protected function createChat(CreateChat $dto): void
    {
        if (ChatEloquentBuilder::query()
                               ->where('uuid', $dto->chatUuid)
                               ->exists()) {
            throw new InvalidArgumentException("Chat with uuid '$dto->chatUuid' already exists");
        }

        /** @var User $user */
        $user = $this->manager->find(User::class, $dto->userUuid);

        $chat = $user->createChat($dto->chatUuid, $dto->chatName);

        $this->manager->persist($chat);
        $this->manager->flush();

        $this->dispatcher->dispatchMany($chat->releaseEvents());
    }

    protected function joinChat(JoinChat $dto): void
    {
        /** @var User $user */
        $user = $this->manager->find(User::class, $dto->userUuid);

        /** @var Chat $chat */
        $chat = $this->manager->find(Chat::class, $dto->chatUuid);

        $user->joinChat($chat);

        $this->manager->flush();

        $this->dispatcher->dispatchMany($chat->releaseEvents());
    }

    protected function kickOutFromChat(KickUserOutFromChat $dto): void
    {
        /** @var User $user */
        $user = $this->manager->find(User::class, $dto->userUuid);

        /** @var Chat $chat */
        $chat = $this->manager->find(Chat::class, $dto->chatUuid);

        $chat->kickUserOut($user);

        $this->manager->flush();

        $this->dispatcher->dispatchMany($chat->releaseEvents());
    }

    protected function leaveChat(LeaveChat $dto): void
    {
        /** @var User $user */
        $user = $this->manager->find(User::class, $dto->userUuid);

        /** @var Chat $chat */
        $chat = $this->manager->find(Chat::class, $dto->chatUuid);

        $user->leaveChat($chat);

        $this->manager->flush();

        $this->dispatcher->dispatchMany($chat->releaseEvents());
    }
}
