<?php

declare(strict_types=1);


namespace Tests\Unit\Domain;

use App\Models\User\Doctrine\User;
use App\Models\User\Events\UserChangedLogin;
use App\Models\User\Events\UserRegistered;
use App\Models\User\VO\Login;
use App\Models\User\VO\Password;
use App\Models\User\VO\UserName;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserTest extends TestCase
{
    public function testRegisterUser(): void
    {
        $user = User::register(
            Uuid::uuid4(),
            Login::fromString('johndoe@example.com'),
            Password::fromHash('$2y$04$yBaFgOPXI0UxxcFV8QGGnedjJwIEQdWIyNFJANqGLR/g25RZfyXGy'),
            UserName::fromString('John Doe'),
        );

        $events = $user->releaseEvents();
        self::assertCount(1, $events);

        /** @var UserRegistered $event */
        $event = end($events);
        self::assertInstanceOf(UserRegistered::class, $event);

        self::assertEquals(UserName::fromString('John Doe'), $event->getUserName());
        self::assertEquals(Login::fromString('johndoe@example.com'), $event->getLogin());
        self::assertTrue($event->getPassword()->verify('hello world'));
    }

    public function testChangeLogin(): void
    {
        $user = User::register(
            Uuid::uuid4(),
            Login::fromString('johndoe@example.com'),
            Password::fromHash('$2y$04$yBaFgOPXI0UxxcFV8QGGnedjJwIEQdWIyNFJANqGLR/g25RZfyXGy'),
            UserName::fromString('John Doe'),
        );

        $user->releaseEvents();

        $user->changeLogin(Login::fromString('janedoe@example.com'));

        $events = $user->releaseEvents();
        self::assertCount(1, $events);

        /** @var UserChangedLogin $event */
        $event = end($events);
        self::assertInstanceOf(UserChangedLogin::class, $event);

        self::assertEquals(Login::fromString('johndoe@example.com'), $event->getOldLogin());
        self::assertEquals(Login::fromString('janedoe@example.com'), $event->getNewLogin());
    }
}
