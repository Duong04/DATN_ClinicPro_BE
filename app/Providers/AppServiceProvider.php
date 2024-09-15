<?php

namespace App\Providers;

use App\Repositories\Package\PackageRepositoryInterface;
use App\Services\CloundinaryService;
use App\Services\PackageService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PackageService::class, function ($app) {
            return new PackageService($app->make(PackageRepositoryInterface::class), $app->make(CloundinaryService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
