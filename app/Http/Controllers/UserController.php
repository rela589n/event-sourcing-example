<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User\Eloquent\Builder\UserEloquentBuilder;
use App\Services\User\Commands\ChangeUserLogin;
use App\Services\User\Commands\ChangeUserPassword;
use App\Services\User\Commands\RegisterUser;
use App\Services\User\UserBus;
use Illuminate\Http\Request;

final class UserController extends Controller
{
    public function __construct(private UserBus $bus)
    {
    }

    public function register(Request $request)
    {
        $this->bus->handle($command = RegisterUser::fromRequest($request));

        return UserEloquentBuilder::query()
            ->findOrFail($command->userUuid);
    }

    public function changeLogin(Request $request)
    {
        $this->bus->handle($command = ChangeUserLogin::fromRequest($request));

        return UserEloquentBuilder::query()
            ->findOrFail($command->userUuid);
    }

    public function changePassword(Request $request)
    {
        $this->bus->handle($command = ChangeUserPassword::fromRequest($request));

        return UserEloquentBuilder::query()
            ->findOrFail($command->userUuid);
    }
}
