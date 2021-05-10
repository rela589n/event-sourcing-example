<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Services\User\Commands\ChangeUserLogin;
use App\Services\User\Commands\ChangeUserPassword;
use App\Services\User\Commands\RegisterUser;
use Illuminate\Http\Request;

abstract class UserCommand
{
    public static function fromRequest(Request $request): self
    {
        return match ($request->get('command', '')) {
            'register' => RegisterUser::fromRequest($request),
            'changeLogin' => ChangeUserLogin::fromRequest($request),
            'changePassword' => ChangeUserPassword::fromRequest($request),
        };
    }
}
