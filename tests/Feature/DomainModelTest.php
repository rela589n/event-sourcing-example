<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Chat\Doctrine\Chat;
use App\Models\Chat\Events\ChatCreated;
use App\Models\Chat\Events\ChatEvent;
use App\Models\Chat\VO\ChatName;
use App\Models\Message\Doctrine\Message;
use App\Models\Message\Events\MessageEvent;
use App\Models\Message\Events\MessageWritten;
use App\Models\Message\VO\MessageContent;
use App\Models\User\Doctrine\User;
use App\Models\User\Events\UserEvent;
use App\Models\User\Events\UserRegistered;
use App\Models\User\VO\Login;
use App\Models\User\VO\Password;
use App\Models\User\VO\UserName;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

final class DomainModelTest extends TestCase
{
    private EntityManager $entityManager;
    private EntityRepository $useRepo;
    private EntityRepository $chatRepo;
    private EntityRepository $messageRepo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = app()->make(EntityManager::class);
        $this->useRepo = $this->entityManager->getRepository(User::class);
        $this->chatRepo = $this->entityManager->getRepository(Chat::class);
        $this->messageRepo = $this->entityManager->getRepository(Message::class);
    }

    public function testEmbeddedSerialization(): void
    {
        $email = 'johndoe'.Str::random().'@example.com';

        $user = User::register(
            Uuid::uuid4(),
            Login::fromString($email),
            Password::fromRaw('hello world'),
            UserName::fromString('John Doe'),
        );

        $this->entityManager->persist($user);

        $this->entityManager->flush();
        $this->entityManager->clear();

        /** @var User $found */
        $found = $this->useRepo->find($user->getPrimary());
        self::assertNotSame($user, $found);
        self::assertEquals($user->getPrimary(), $found->getPrimary());

        $repo = $this->entityManager->getRepository(UserEvent::class);
        $events = $repo->findBy(['entity' => $found->getPrimary()]);

        /** @var UserRegistered $event */
        $event = $events[0];
        self::assertInstanceOf(UserRegistered::class, $event);
        self::assertEquals(Login::fromString($email), $event->getLogin());
        self::assertTrue(
            $event->getPassword()
                ->verify('hello world')
        );
        self::assertEquals(UserName::fromString('John Doe'), $events[1]->getUserName());
    }

    public function testCreateChat(): void
    {
        $user = User::register(
            Uuid::uuid4(),
            Login::fromString('johndoe'.Str::random().'@example.com'),
            Password::fromRaw('hello world'),
            UserName::fromString('John Doe'),
        );
        $this->entityManager->persist($user);

        $chat = Chat::create(
            $user,
            Uuid::uuid4(),
            ChatName::fromString('Some name')
        );

        $events = $chat->releaseEvents();
        self::assertCount(2, $events);

        /** @var ChatCreated $event */
        $event = reset($events);
        self::assertInstanceOf(ChatCreated::class, $event);

        self::assertEquals(ChatName::fromString('Some name'), $event->getChatName());

        $this->entityManager->persist($chat);
        $this->entityManager->flush();
        $this->entityManager->clear();

        /** @var Chat $found */
        $found = $this->chatRepo->find($chat->getPrimary());
        self::assertNotSame($chat, $found);
        self::assertEquals($chat->getPrimary(), $found->getPrimary());
    }

    public function testUserCantCreateMoreThanFiveChatsPerDay(): void
    {
        $user = User::register(
            Uuid::uuid4(),
            Login::fromString('johndoe'.Str::random().'@example.com'),
            Password::fromRaw('hello world'),
            UserName::fromString('John Doe'),
        );
        $chats = [];


        $chats [] = $user->createChat(Uuid::uuid6(), ChatName::fromString('Some name'));
        $chats [] = $user->createChat(Uuid::uuid6(), ChatName::fromString('Some name'));
        $chats [] = $user->createChat(Uuid::uuid6(), ChatName::fromString('Some name'));
        dd($user->chatsCreatedToday());

        $chats [] = Chat::create($user, Uuid::uuid4(), ChatName::fromString('Some name'));
        $chats [] = Chat::create($user, Uuid::uuid4(), ChatName::fromString('Some name'));

        $this->expectException(InvalidArgumentException::class);
        $chats [] = Chat::create($user, Uuid::uuid4(), ChatName::fromString('Some name'));

    }

    public function testWriteMessage(): void
    {
        $user = User::register(
            Uuid::uuid4(),
            Login::fromString('johndoe'.Str::random().'@example.com'),
            Password::fromRaw('hello world'),
            UserName::fromString('John Doe'),
        );
        $this->entityManager->persist($user);

        $chat = Chat::create(
            $user,
            Uuid::uuid4(),
            ChatName::fromString('Some name')
        );
        $this->entityManager->persist($chat);

        $message = Message::write(
            Uuid::uuid4(),
            $user,
            $chat,
            MessageContent::fromString('Some message')
        );

        $events = $message->releaseEvents();
        self::assertCount(1, $events);

        /** @var MessageWritten $event */
        $event = end($events);
        self::assertInstanceOf(MessageWritten::class, $event);

        self::assertSame($user, $event->getUser());
        self::assertSame($chat, $event->getChat());
        self::assertEquals(MessageContent::fromString('Some message'), $event->getContent());

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $this->entityManager->clear();

        $mesEventsRepo = $this->entityManager->getRepository(MessageEvent::class);

        $events = $mesEventsRepo->findBy(['entity' => $message]);
        /** @var MessageWritten $ev */
        $ev = $events[0];

        dd(
            $ev->getUser()
                ->getPrimary()
        );
    }

    public function testFindChat(): void
    {
        $repo = $this->entityManager->getRepository(ChatEvent::class);
        $event = $repo->findOneBy(['entity' => 'ba315590-6039-48b0-b444-be34a0ef06ec']);
        /** @var ChatCreated $event */
        $chat = $event->getEntity();
        dump($chat->getPrimary());
        dd($chat);
        dd($event);
    }
}
