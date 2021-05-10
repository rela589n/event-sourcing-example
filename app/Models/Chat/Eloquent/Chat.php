<?php

declare(strict_types=1);

namespace App\Models\Chat\Eloquent;

use App\Casts\UuidCast;
use App\Infrastructure\Database\AppModel;
use App\Infrastructure\Database\Concerns\ReadModel;
use App\Infrastructure\Database\Eloquent\EloquentBuilder;
use App\Infrastructure\Database\Eloquent\EloquentCollection;
use App\Models\Chat\Casts\ChatNameCast;
use App\Models\Chat\Eloquent\Builder\ChatEloquentBuilder;
use App\Models\Chat\VO\ChatName;
use App\Models\Message\Eloquent\Message;
use App\Models\User\Eloquent\User;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Chat\Eloquent\Chat
 *
 * @property \Ramsey\Uuid\UuidInterface $uuid
 * @property ChatName $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static EloquentCollection|static[] all($columns = ['*'])
 * @method static EloquentCollection|static[] get($columns = ['*'])
 * @method static ChatEloquentBuilder|Chat newModelQuery()
 * @method static ChatEloquentBuilder|Chat newQuery()
 * @method static ChatEloquentBuilder|Chat query()
 * @method static ChatEloquentBuilder|Chat whereCreatedAt($value)
 * @method static ChatEloquentBuilder|Chat whereName($value)
 * @method static ChatEloquentBuilder|Chat whereUpdatedAt($value)
 * @method static ChatEloquentBuilder|Chat whereUuid($value)
 * @mixin Eloquent
 * @property-read EloquentCollection|User[] $users
 * @property-read int|null $users_count
 * @property-read \App\Models\Message\Eloquent\Collections\MessagesEloquentCollection|Message[] $messages
 * @property-read int|null $messages_count
 * @property \Ramsey\Uuid\UuidInterface|null $user_uuid
 * @property-read User $user
 * @method static ChatEloquentBuilder|Chat whereUserUuid($value)
 */
final class Chat extends AppModel
{
    use ReadModel;

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $casts = [
        'uuid' => UuidCast::class.':uuid',
        'user_uuid' => UuidCast::class.':user_uuid',
        'name' => ChatNameCast::class,
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_chat',
            'chat_uuid',
            'user_uuid',
            'uuid',
            'uuid',
        );
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_uuid', 'uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function newEloquentBuilder($query): EloquentBuilder
    {
        return new ChatEloquentBuilder($query);
    }
}
