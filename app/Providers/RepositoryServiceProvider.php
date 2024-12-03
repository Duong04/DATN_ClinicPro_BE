<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Action\ActionRepository;
use App\Repositories\Package\PackageRepository;
use App\Repositories\Patient\PatientRepository;
use App\Repositories\Feedback\FeedbackRepository;
use App\Repositories\UserInfo\UserInfoRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Specialty\SpecialtyRepository;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\UserDetail\UserDetailRepository;
use App\Repositories\Action\ActionRepositoryInterface;
use App\Repositories\Appointment\AppointmentRepository;
use App\Repositories\PatientInfo\PatientInfoRepository;
use App\Repositories\Package\PackageRepositoryInterface;
use App\Repositories\Patient\PatientRepositoryInterface;
use App\Repositories\Prescription\PrescriptionRepository;
use App\Repositories\Feedback\FeedbackRepositoryInterface;
use App\Repositories\UserInfo\UserInfoRepositoryInterface;
use App\Repositories\Specialty\SpecialtyRepositoryInterface;
use App\Repositories\MedicalHistory\MedicalHistoryRepository;
use App\Repositories\RolePermission\RolePermissionRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\UserDetail\UserDetailRepositoryInterface;
use App\Repositories\PackageCategory\PackageCategoryRepository;
use App\Repositories\Appointment\AppointmentRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;
use App\Repositories\PermissionAction\PermissionActionRepository;
use App\Repositories\PrescriptionInfo\PrescriptionInfoRepository;
use App\Repositories\Prescription\PrescriptionRepositoryInterface;
use App\Repositories\MedicalHistory\MedicalHistoryRepositoryInterface;
use App\Repositories\RolePermission\RolePermissionRepositoryInterface;
use App\Repositories\PackageCategory\PackageCategoryRepositoryInterface;
use App\Repositories\PermissionAction\PermissionActionRepositoryInterface;
use App\Repositories\PrescriptionInfo\PrescriptionInfoRepositoryInterface;
use App\Repositories\Conversation\ConversationRepositoryInterface;
use App\Repositories\Conversation\ConversationRepository;
use App\Repositories\Message\MessageRepositoryInterface;
use App\Repositories\Message\MessageRepository;

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
        $this->app->bind(UserInfoRepositoryInterface::class, UserInfoRepository::class);
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(AppointmentRepositoryInterface::class, AppointmentRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(MedicalHistoryRepositoryInterface::class, MedicalHistoryRepository::class);
        $this->app->bind(PrescriptionRepositoryInterface::class, PrescriptionRepository::class);
        $this->app->bind(SpecialtyRepositoryInterface::class, SpecialtyRepository::class);
        $this->app->bind(FeedbackRepositoryInterface::class, FeedbackRepository::class);
        $this->app->bind(PrescriptionInfoRepositoryInterface::class, PrescriptionInfoRepository::class);
        $this->app->bind(PackageCategoryRepositoryInterface::class, PackageCategoryRepository::class);
        $this->app->bind(ConversationRepositoryInterface::class, ConversationRepository::class);
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
