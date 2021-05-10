<?php

declare(strict_types=1);

namespace App\Models\Chat\Casts;

use App\Models\Chat\Doctrine\Chat as DoctrineChat;
use App\Models\Chat\Eloquent\Chat as EloquentChat;
use App\Models\Chat\VO\ChatName;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes as CastsAttributesEloquent;
use Rela589n\DoctrineEventSourcing\Serializer\Separate\Castable\Contract\CastsAttributes;
use Webmozart\Assert\Assert;

class ChatNameCast implements CastsAttributesEloquent, CastsAttributes
{
    public function get($model, $key, $value, $attributes): ChatName
    {
        Assert::isInstanceOfAny($model, [EloquentChat::class, DoctrineChat::class]);

        return ChatName::fromString(
            $attributes['name'],
        );
    }

    public function set($model, $key, $value, $attributes): array
    {
        Assert::isInstanceOfAny($model, [EloquentChat::class, DoctrineChat::class]);
        Assert::isInstanceOf($value, ChatName::class);

        return [
            'name' => (string)$value,
        ];
    }
}
