<?php

declare(strict_types=1);

namespace App\Services\Message\Commands;

use App\Models\Message\VO\MessageContent;
use App\Services\Message\MessageCommand;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Immutable]
final class WriteMessage extends MessageCommand
{
    public function __construct(
        public UuidInterface $userUuid,
        public UuidInterface $chatUuid,
        public UuidInterface $messageUuid,
        public MessageContent $content,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            Uuid::fromString($request->get('userUuid', '')),
            Uuid::fromString($request->get('chatUuid', '')),
            Uuid::fromString($request->get('messageUuid', '')),
            MessageContent::fromString($request->get('content', '')),
        );
    }
}
