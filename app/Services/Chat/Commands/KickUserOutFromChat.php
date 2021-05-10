<?php

declare(strict_types=1);

namespace App\Services\Chat\Commands;

use App\Services\Chat\ChatCommand;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Immutable]
final class KickUserOutFromChat extends ChatCommand
{
    public function __construct(
        public UuidInterface $userUuid,
        public UuidInterface $chatUuid,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            Uuid::fromString($request->get('userUuid', '')),
            Uuid::fromString($request->get('chatUuid', '')),
        );
    }
}
