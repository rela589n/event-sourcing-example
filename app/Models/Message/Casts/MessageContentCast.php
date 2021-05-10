<?php

declare(strict_types=1);

namespace App\Models\Message\Casts;

use App\Models\Message\Doctrine\Message as DoctrineMessage;
use App\Models\Message\Eloquent\Message as EloquentMessage;
use App\Models\Message\VO\MessageContent;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes as CastsAttributesEloquent;
use Rela589n\DoctrineEventSourcing\Serializer\Separate\Castable\Contract\CastsAttributes;
use Webmozart\Assert\Assert;

class MessageContentCast implements CastsAttributesEloquent, CastsAttributes
{
    public function get($model, $key, $value, $attributes): MessageContent
    {
        Assert::isInstanceOfAny($model, [EloquentMessage::class, DoctrineMessage::class]);

        return MessageContent::fromString($attributes['content']);
    }

    public function set($model, $key, $value, $attributes): array
    {
        Assert::isInstanceOfAny($model, [EloquentMessage::class, DoctrineMessage::class]);
        Assert::isInstanceOf($value, MessageContent::class);

        return [
            'content' => (string)$value,
        ];
    }
}
