<?php

declare(strict_types=1);

namespace App\Models\User\VO;

use App\Models\User\Casts\UserPasswordCast;
use App\Models\User\VO\Exceptions\PasswordTooLongException;
use App\Models\User\VO\Exceptions\PasswordTooShortException;
use Illuminate\Contracts\Database\Eloquent\Castable as EloquentCastable;
use Rela589n\DoctrineEventSourcing\Serializer\Separate\Castable\Contract\Castable;

final class Password implements EloquentCastable, Castable
{
    public const MIN_LENGTH = 6;
    public const MAX_LENGTH = 64;

    private string $passwordHash;

    private function __construct(string $hash)
    {
        $this->passwordHash = $hash;
    }

    public static function fromRaw(string $rawPassword): self
    {
        $len = mb_strlen($rawPassword);
        if ($len < self::MIN_LENGTH) {
            throw new PasswordTooShortException($rawPassword, self::MIN_LENGTH);
        }

        if ($len > self::MAX_LENGTH) {
            throw new PasswordTooLongException($rawPassword, self::MAX_LENGTH);
        }

        return new self(bcrypt($rawPassword));
    }

    public static function fromHash(string $hash): self
    {
        return new self($hash);
    }

    public function __toString(): string
    {
        return $this->hash();
    }

    public function hash(): string
    {
        return $this->passwordHash;
    }

    public function verify(string $rawPassword): bool
    {
        return password_verify($rawPassword, (string)$this);
    }

    public static function castUsing(array $arguments): UserPasswordCast
    {
        return new UserPasswordCast();
    }
}
