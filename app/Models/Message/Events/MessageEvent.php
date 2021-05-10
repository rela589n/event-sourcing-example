<?php

declare(strict_types=1);


namespace App\Models\Message\Events;

use App\Models\Message\Doctrine\Message;
use Rela589n\DoctrineEventSourcing\Event\AggregateChanged;

abstract class MessageEvent extends AggregateChanged
{
    public function __construct(Message $message)
    {
        parent::__construct($message);
    }
}
