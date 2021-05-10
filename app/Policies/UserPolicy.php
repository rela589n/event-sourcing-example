<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User\Eloquent\User;
use App\Services\User\Commands\ChangeUserLogin;
use App\Services\User\Commands\ChangeUserPassword;
use App\Services\User\Commands\RegisterUser;
use App\Services\User\UserBus;

/** @see UserBus */
final class UserPolicy
{
    public function register(?User $user, RegisterUser $dto): bool
    {
        return true;
    }

    public function changeLogin(?User $user, ChangeUserLogin $dto): bool
    {
        return true;
    }

    public function changePassword(?User $user, ChangeUserPassword $dto): bool
    {
        return true;
    }
}
