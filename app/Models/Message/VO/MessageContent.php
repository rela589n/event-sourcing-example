<?php

declare(strict_types=1);

namespace App\Models\Message\VO;

use App\Models\Message\Casts\MessageContentCast;
use Illuminate\Contracts\Database\Eloquent\Castable as EloquentCastable;
use JsonSerializable;
use Rela589n\DoctrineEventSourcing\Serializer\Separate\Castable\Contract\Castable;

final class MessageContent implements EloquentCastable, Castable, JsonSerializable
{
    private string $content;

    private function __construct(string $content)
    {
        $this->content = $content;
    }

    public static function fromString(string $content): self
    {
        return new self($content);
    }

    public function jsonSerialize(): string
    {
        return (string)$this;
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public static function castUsing(array $arguments): MessageContentCast
    {
        return new MessageContentCast();
    }
}
