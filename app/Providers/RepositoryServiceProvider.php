<?php

namespace App\Providers;

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
use App\Repositories\PermissionAction\PermissionActionRepositoryInterface;
use App\Repositories\PermissionAction\PermissionActionRepository;
use App\Repositories\RolePermission\RolePermissionRepositoryInterface;
use App\Repositories\RolePermission\RolePermissionRepository;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepository;
use App\Repositories\Patient\PatientRepository;
use App\Repositories\Patient\PatientRepositoryInterface;

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
        $this->app->bind(PermissionActionRepositoryInterface::class, PermissionActionRepository::class);
        $this->app->bind(RolePermissionRepositoryInterface::class, RolePermissionRepository::class);
        $this->app->bind(PatientInfoRepositoryInterface::class, PatientInfoRepository::class);
        $this->app->bind(PatientRepositoryInterface::class, PatientRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
