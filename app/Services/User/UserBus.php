<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Infrastructure\Events\EventDispatcher;
use App\Infrastructure\Services\ServiceBus;
use App\Models\User\Doctrine\User;
use App\Models\User\Eloquent\Builder\UserEloquentBuilder;
use App\Models\User\Events\UserChangedLogin;
use App\Services\User\Commands\ChangeUserLogin;
use App\Services\User\Commands\ChangeUserPassword;
use App\Services\User\Commands\RegisterUser;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use JetBrains\PhpStorm\Immutable;
use LogicException;
use Webmozart\Assert\Assert;

use function array_filter;
use function array_shift;

#[Immutable]
final class UserBus extends ServiceBus
{
    use ServiceBus\PipedBus;

    public function __construct(
        private EntityManager $manager,
        private EventDispatcher $dispatcher,
    ) {
    }

    public function handle(object $command)
    {
        Assert::isInstanceOf($command, UserCommand::class);

        return parent::handle($command);
    }

    protected function getHandler(object $command): array
    {
        return match (true) {
            $command instanceof RegisterUser => [$this, 'register'],
            $command instanceof ChangeUserLogin => [$this, 'changeLogin'],
            $command instanceof ChangeUserPassword => [$this, 'changePassword'],
            default => throw new LogicException('Unknown command: '.$command::class)
        };
    }

    protected function register(RegisterUser $dto): void
    {
        if (UserEloquentBuilder::query()
            ->where('uuid', $dto->userUuid)
            ->exists()) {
            throw new InvalidArgumentException("Uuid '$dto->userUuid' is already taken");
        }

        if (UserEloquentBuilder::query()
            ->whereLogin($dto->login)
            ->exists()) {
            throw new InvalidArgumentException("User with '$dto->login' login already exists");
        }

        $user = User::register($dto->userUuid, $dto->login, $dto->password, $dto->name);

        $this->manager->persist($user);
        $this->manager->flush($user);

        $this->dispatcher->dispatchMany($user->releaseEvents());
    }

    protected function changeLogin(ChangeUserLogin $dto): void
    {
        /** @var User $user */
        $user = $this->manager->find(User::class, $dto->userUuid);

        $user->changeLogin($dto->newLogin);

        $events = $user->releaseEvents();
        $event = $this->getChangedLoginEvent($events);

        if (!$event->getNewLogin()->equals($event->getOldLogin())
            && UserEloquentBuilder::query()
                ->whereLogin($dto->newLogin)
                ->exists()) {
            $user->changeLogin($event->getOldLogin());
            throw new InvalidArgumentException("User with '$dto->newLogin' login already exists");
        }

        $this->manager->flush();
        $this->dispatcher->dispatchMany($events);
    }

    protected function getChangedLoginEvent(array $events): UserChangedLogin
    {
        $changedLogin = array_filter($events, static fn($e) => $e instanceof UserChangedLogin);
        Assert::count($changedLogin, 1);

        return array_shift($changedLogin);
    }

    protected function changePassword(ChangeUserPassword $dto): void
    {
        /** @var User $user */
        $user = $this->manager->find(User::class, $dto->userUuid);

        $user->changePassword($dto->newPassword);

        $this->manager->flush();

        $this->dispatcher->dispatchMany($user->releaseEvents());
    }
}
