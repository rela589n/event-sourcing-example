<?php

namespace App\Models\User\Eloquent;

use App\Casts\UuidCast;
use App\Infrastructure\Database\Concerns\OverridesEloquentBuilder;
use App\Infrastructure\Database\Concerns\OverridesEloquentCollection;
use App\Infrastructure\Database\Concerns\OverridesQueryBuilder;
use App\Infrastructure\Database\Concerns\ReadModel;
use App\Infrastructure\Database\Eloquent\EloquentBuilder;
use App\Infrastructure\Database\Eloquent\EloquentCollection;
use App\Models\User\Casts\UserLoginCast;
use App\Models\User\Casts\UserPasswordCast;
use App\Models\User\Eloquent\Builder\UserEloquentBuilder;
use App\Models\User\VO\Password;
use Eloquent;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * App\Models\User\Eloquent\User
 *
 * @property \Ramsey\Uuid\UuidInterface $uuid
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property Password $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static EloquentCollection|static[] all($columns = ['*'])
 * @method static EloquentCollection|static[] get($columns = ['*'])
 * @method static UserEloquentBuilder|User newModelQuery()
 * @method static UserEloquentBuilder|User newQuery()
 * @method static UserEloquentBuilder|User query()
 * @method static UserEloquentBuilder|User whereCreatedAt($value)
 * @method static UserEloquentBuilder|User whereEmail($value)
 * @method static UserEloquentBuilder|User whereEmailVerifiedAt($value)
 * @method static UserEloquentBuilder|User whereLogin(\App\Models\User\VO\Login $login)
 * @method static UserEloquentBuilder|User whereName($value)
 * @method static UserEloquentBuilder|User wherePassword($value)
 * @method static UserEloquentBuilder|User whereRememberToken($value)
 * @method static UserEloquentBuilder|User whereUpdatedAt($value)
 * @method static UserEloquentBuilder|User whereUuid($value)
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use OverridesQueryBuilder;
    use OverridesEloquentBuilder;
    use OverridesEloquentCollection;

    use ReadModel;
    use Notifiable;

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'uuid' => UuidCast::class.':uuid',
        'email_verified_at' => 'datetime',
        'login' => UserLoginCast::class,
        'password' => UserPasswordCast::class,
    ];

    public function newEloquentBuilder($query): EloquentBuilder
    {
        return new UserEloquentBuilder($query);
    }
}
