<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Chat\ChatCommand;
use App\Services\Message\MessageCommand;
use App\Services\User\UserCommand;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
abstract class UniversalCommand
{
    public static function fromRequest(Request $request)
    {
        return match ($request->get('scope', '')) {
            'Message' => MessageCommand::fromRequest($request),
            'User' => UserCommand::fromRequest($request),
            'Chat' => ChatCommand::fromRequest($request),
        };
    }
}
