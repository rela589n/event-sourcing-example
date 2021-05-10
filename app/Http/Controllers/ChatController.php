<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Chat\Eloquent\Builder\ChatEloquentBuilder;
use App\Models\User\Eloquent\Builder\UserEloquentBuilder;
use App\Services\Chat\ChatBus;
use App\Services\Chat\Commands\CreateChat;
use App\Services\Chat\Commands\JoinChat;
use App\Services\Chat\Commands\KickUserOutFromChat;
use App\Services\Chat\Commands\LeaveChat;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class ChatController extends Controller
{
    public function __construct(private ChatBus $bus) { }

    public function show(Request $request, string $chatUuid)
    {
        return [
            'data' => ChatEloquentBuilder::query()
                ->where('uuid', $chatUuid)
                ->with('users')
                ->with('messages')
                ->orderByDesc('created_at')
                ->firstOrFail(),
        ];
    }

    public function create(Request $request)
    {
        $this->bus->handle($command = CreateChat::fromRequest($request));

        return ChatEloquentBuilder::query()
            ->findOrFail($command->chatUuid);
    }

    public function joinChat(Request $request)
    {
        $this->bus->handle($command = JoinChat::fromRequest($request));

        return ChatEloquentBuilder::query()
            ->findOrFail($command->chatUuid);
    }

    public function leaveChat(Request $request)
    {
        $this->bus->handle($command = LeaveChat::fromRequest($request));

        return ChatEloquentBuilder::query()
            ->findOrFail($command->chatUuid);
    }

    public function kickOut(Request $request)
    {
        $this->bus->handle($command = KickUserOutFromChat::fromRequest($request));

        return UserEloquentBuilder::query()
            ->findOrFail($command->userUuid);
    }
}
