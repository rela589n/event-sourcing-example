<?php

namespace App\Providers;

use App\Infrastructure\Services\Pipes\Guarded;
use App\Services\Chat\ChatBus;
use App\Services\Message\MessageBus;
use App\Services\User\UserBus;
use Illuminate\Support\ServiceProvider;

use function app;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MessageBus::class);
        $this->app->extend(
            MessageBus::class,
            fn(MessageBus $s) => $s->addPipe([app(Guarded::class), 'handle'])
        );

        $this->app->singleton(UserBus::class);
        $this->app->extend(
            UserBus::class,
            fn(UserBus $s) => $s->addPipe([app(Guarded::class), 'handle'])
        );

        $this->app->singleton(ChatBus::class);
        $this->app->extend(
            ChatBus::class,
            fn(ChatBus $s) => $s->addPipe([app(Guarded::class), 'handle']),
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
