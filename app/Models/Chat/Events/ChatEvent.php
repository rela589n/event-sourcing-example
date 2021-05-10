<?php

declare(strict_types=1);


namespace App\Models\Chat\Events;

use App\Models\Chat\Doctrine\Chat;
use Rela589n\DoctrineEventSourcing\Event\AggregateChanged;

abstract class ChatEvent extends AggregateChanged
{
    public function __construct(Chat $chat)
    {
        parent::__construct($chat);
    }
}
