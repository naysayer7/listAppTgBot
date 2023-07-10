<?php

namespace App\Providers;

use App\Services\ListService;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TelegramService::class, function () {
            return new TelegramService(config('app.telegram_api_token'));
        });

        $this->app->singleton(ListService::class, function () {
            return new ListService(config('app.backend_url'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
