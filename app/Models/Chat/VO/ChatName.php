<?php

declare(strict_types=1);


namespace App\Models\Chat\VO;

use App\Models\Chat\Casts\ChatNameCast;
use Illuminate\Contracts\Database\Eloquent\Castable as EloquentCastable;
use JsonSerializable;
use Rela589n\DoctrineEventSourcing\Serializer\Separate\Castable\Contract\Castable;

final class ChatName implements EloquentCastable, Castable, JsonSerializable
{
    private string $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public static function castUsing(array $arguments): ChatNameCast
    {
        return new ChatNameCast();
    }

    public function jsonSerialize()
    {
        return (string)$this;
    }
}
