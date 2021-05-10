<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\UniversalCommand;
use App\Services\UniversalService;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class UniversalController
{
    public function __construct(private UniversalService $service) { }

    public function runCommand(Request $request)
    {
        $this->service->handle(
            UniversalCommand::fromRequest($request)
        );
    }
}
