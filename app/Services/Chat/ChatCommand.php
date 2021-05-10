<?php

declare(strict_types=1);

namespace App\Services\Chat;

use App\Services\Chat\Commands\CreateChat;
use App\Services\Chat\Commands\JoinChat;
use App\Services\Chat\Commands\KickUserOutFromChat;
use App\Services\Chat\Commands\LeaveChat;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
abstract class ChatCommand
{
    public static function fromRequest(Request $request): self
    {
        return match ($request->get('command', '')) {
            'createChat' => CreateChat::fromRequest($request),
            'joinChat' => JoinChat::fromRequest($request),
            'kickOutFromChat' => KickUserOutFromChat::fromRequest($request),
            'leaveChat' => LeaveChat::fromRequest($request),
        };
    }
}
