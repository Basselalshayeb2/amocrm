<?php

namespace App\Providers;

use App\Services\AmoAuthService;
use App\Services\AmoService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        $this->app->singleton(AmoService::class, function ($app) {
            return new AmoService();
        });
        $this->app->singleton(AmoAuthService::class, function ($app) {
            return new AmoAuthService();
        });
        $this->app->singleton(AmoService::class, function ($app) {
            return new AmoService();
        });
    }
}
