<?php

declare(strict_types=1);

namespace App\Services\Message;

use App\Services\Message\Commands\DeleteMessage;
use App\Services\Message\Commands\EditMessage;
use App\Services\Message\Commands\WriteMessage;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
abstract class MessageCommand
{
    public static function fromRequest(Request $request): self
    {
        return match ($request->get('command', '')) {
            'write' => WriteMessage::fromRequest($request),
            'edit' => EditMessage::fromRequest($request),
            'delete' => DeleteMessage::fromRequest($request),
        };
    }
}
