<?php

declare(strict_types=1);

namespace App\Models\User\Events;

use App\Models\User\Doctrine\User;
use App\Models\User\VO\UserName;
use JetBrains\PhpStorm\Immutable;
use Rela589n\DoctrineEventSourcing\Event\Annotations\SerializeAs;

#[Immutable]
class UserSetName extends UserEvent
{
    #[SerializeAs(name: 'user_name')]
    private UserName $userName;

    public function __construct(User $user, UserName $userName)
    {
        parent::__construct($user);
        $this->userName = $userName;
    }

    public static function with(User $user, UserName $userName): self
    {
        return new self($user, $userName);
    }

    public function getUserName(): UserName
    {
        return $this->userName;
    }

    public function NAME(): string
    {
        return 'user_set_name';
    }
}
