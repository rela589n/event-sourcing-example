<?php

declare(strict_types=1);

namespace App\Services\User\Commands;

use App\Models\User\VO\Login;
use App\Models\User\VO\Password;
use App\Models\User\VO\UserName;
use App\Services\User\UserCommand;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\UuidInterface as Uuid;

#[Immutable]
final class RegisterUser extends UserCommand
{
    public function __construct(
        public Uuid $userUuid,
        public Login $login,
        public Password $password,
        public UserName $name,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            \Ramsey\Uuid\Uuid::fromString($request->get('uuid', '')),
            Login::fromString($request->get('login', '')),
            Password::fromRaw($request->get('password', '')),
            UserName::fromString($request->get('name', '')),
        );
    }
}
