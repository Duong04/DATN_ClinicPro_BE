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
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
