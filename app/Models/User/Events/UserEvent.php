<?php

declare(strict_types=1);

namespace App\Models\User\Events;

use App\Models\User\Doctrine\User;
use Rela589n\DoctrineEventSourcing\Event\AggregateChanged;

abstract class UserEvent extends AggregateChanged
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}
