<?php

declare(strict_types=1);

namespace App\Services\User\Commands;

use App\Models\User\VO\Password;
use App\Services\User\UserCommand;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Immutable]
final class ChangeUserPassword extends UserCommand
{
    public function __construct(
        public UuidInterface $userUuid,
        public Password $newPassword,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            Uuid::fromString($request->get('uuid', '')),
            Password::fromRaw($request->get('password', '')),
        );
    }
}
