<?php

namespace App\Providers;

use App\Repositories\PermissionAction\PermissionActionRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserDetail\UserDetailRepositoryInterface;
use App\Repositories\UserDetail\UserDetailRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Action\ActionRepositoryInterface;
use App\Repositories\Action\ActionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\RolePermission\RolePermissionRepositoryInterface;
use App\Repositories\RolePermission\RolePermissionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserDetailRepositoryInterface::class, UserDetailRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(ActionRepositoryInterface::class, ActionRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionActionRepository::class);
        $this->app->bind(RolePermissionRepositoryInterface::class, RolePermissionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
