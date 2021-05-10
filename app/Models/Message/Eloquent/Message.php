<?php

declare(strict_types=1);

namespace App\Models\Message\Eloquent;

use App\Casts\UuidCast;
use App\Infrastructure\Database\AppModel;
use App\Infrastructure\Database\Concerns\ReadModel;
use App\Infrastructure\Database\Eloquent\EloquentBuilder;
use App\Models\Chat\Eloquent\Chat;
use App\Models\Message\Casts\MessageContentCast;
use App\Models\Message\Eloquent\Builder\MessageEloquentBuilder;
use App\Models\Message\Eloquent\Collections\MessagesEloquentCollection;
use App\Models\Message\VO\MessageContent;
use App\Models\User\Eloquent\User;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Message\Eloquent\Message
 *
 * @property \Ramsey\Uuid\UuidInterface $uuid
 * @property string $status
 * @property MessageContent $content
 * @property \Ramsey\Uuid\UuidInterface $user_uuid
 * @property \Ramsey\Uuid\UuidInterface $chat_uuid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Chat $chat
 * @property-read User $user
 *
 * @method static MessagesEloquentCollection|static[] all($columns = ['*'])
 * @method static MessagesEloquentCollection|static[] get($columns = ['*'])
 * @method static MessageEloquentBuilder|Message newModelQuery()
 * @method static MessageEloquentBuilder|Message newQuery()
 * @method static MessageEloquentBuilder|Message query()
 * @method static MessageEloquentBuilder|Message whereChatUuid($value)
 * @method static MessageEloquentBuilder|Message whereContent($value)
 * @method static MessageEloquentBuilder|Message whereCreatedAt($value)
 * @method static MessageEloquentBuilder|Message whereStatus($value)
 * @method static MessageEloquentBuilder|Message whereUpdatedAt($value)
 * @method static MessageEloquentBuilder|Message whereUserUuid($value)
 * @method static MessageEloquentBuilder|Message whereUuid($value)
 * @mixin Eloquent
 */
final class Message extends AppModel
{
    use ReadModel;

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $casts = [
        'uuid' => UuidCast::class.':uuid',
        'chat_uuid' => UuidCast::class.':chat_uuid',
        'user_uuid' => UuidCast::class.':chat_uuid',
        'content' => MessageContentCast::class,
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_uuid', 'uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function newCollection(array $models = []): MessagesEloquentCollection
    {
        return new MessagesEloquentCollection($models);
    }

    public function newEloquentBuilder($query): EloquentBuilder
    {
        return new MessageEloquentBuilder($query);
    }
}
