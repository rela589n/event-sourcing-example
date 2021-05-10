<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User\Eloquent\User;
use App\Services\Chat\ChatBus;
use App\Services\Chat\Commands\CreateChat;
use App\Services\Chat\Commands\JoinChat;
use App\Services\Chat\Commands\KickUserOutFromChat;
use App\Services\Chat\Commands\LeaveChat;

/** @see ChatBus */
final class ChatPolicy
{
    public function createChat(?User $user, CreateChat $dto): bool
    {
        return true;
    }

    public function joinChat(?User $user, JoinChat $dto): bool
    {
        return true;
    }

    public function leaveChat(?User $user, LeaveChat $dto): bool
    {
        return true;
    }

    public function kickOutFromChat(?User $user, KickUserOutFromChat $dto): bool
    {
        return true;
    }
}
