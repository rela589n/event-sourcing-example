<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User\Eloquent\User;
use App\Services\Message\Commands\DeleteMessage;
use App\Services\Message\Commands\EditMessage;
use App\Services\Message\Commands\WriteMessage;

final class MessagePolicy
{
    public function writeMessage(?User $user, WriteMessage $dto): bool
    {
        return true;
    }

    public function editMessage(?User $user, EditMessage $dto): bool
    {
        return true;
    }

    public function deleteMessage(?User $user, DeleteMessage $dto): bool
    {
        return true;
    }
}
