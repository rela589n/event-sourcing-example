<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Chat\Eloquent\Builder\ChatEloquentBuilder;
use App\Models\Message\Eloquent\Builder\MessageEloquentBuilder;
use App\Models\User\Eloquent\Builder\UserEloquentBuilder;
use App\Services\Message\Commands\DeleteMessage;
use App\Services\Message\Commands\EditMessage;
use App\Services\Message\Commands\WriteMessage;
use App\Services\Message\MessageBus;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class MessageController extends Controller
{
    public function __construct(private MessageBus $bus) { }

    public function index(Request $request)
    {
        $userUuid = $request->get('userUuid');
        $chatUuid = $request->get('chatUuid');

        return [
            'data' => MessageEloquentBuilder::query()
                ->whereHas('chat', fn(ChatEloquentBuilder $q) => $q->where('uuid', $chatUuid))
                ->whereHas('user', fn(UserEloquentBuilder $q) => $q->where('uuid', $userUuid))
                ->orderByDesc('created_at')
                ->get(),
        ];
    }

    public function write(Request $request)
    {
        $this->bus->handle($command = WriteMessage::fromRequest($request));

        return MessageEloquentBuilder::query()
            ->findOrFail($command->messageUuid);
    }

    public function edit(Request $request)
    {
        $this->bus->handle($command = EditMessage::fromRequest($request));

        return MessageEloquentBuilder::query()
            ->findOrFail($command->messageUuid);
    }

    public function delete(Request $request)
    {
        $this->bus->handle($command = DeleteMessage::fromRequest($request));

        return MessageEloquentBuilder::query()
            ->findOrFail($command->messageUuid);
    }
}
