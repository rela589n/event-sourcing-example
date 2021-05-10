<?php

namespace App\Providers;

use App\Policies\ChatPolicy;
use App\Policies\MessagePolicy;
use App\Policies\UserPolicy;
use App\Services\Chat\ChatCommand;
use App\Services\Message\MessageCommand;
use App\Services\User\UserCommand;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        UserCommand::class => UserPolicy::class,
        ChatCommand::class => ChatPolicy::class,
        MessageCommand::class => MessagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }
}
