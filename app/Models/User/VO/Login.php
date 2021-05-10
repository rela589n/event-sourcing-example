<?php

declare(strict_types=1);

namespace App\Models\User\VO;

use App\Models\User\Casts\UserLoginCast;
use App\Models\User\VO\Exceptions\LoginInvalidException;
use Illuminate\Contracts\Database\Eloquent\Castable as EloquentCastable;
use Rela589n\DoctrineEventSourcing\Serializer\Separate\Castable\Contract\Castable;

final class Login implements EloquentCastable, Castable
{
    private string $email;

    private function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new LoginInvalidException("Email \"$email\" is invalid.");
        }

        $this->email = $email;
    }

    public static function fromString(string $string): self
    {
        return new self($string);
    }

    public function equals(self $login): bool
    {
        return $login->email === $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public static function castUsing(array $arguments)
    {
        return new UserLoginCast();
    }
}
