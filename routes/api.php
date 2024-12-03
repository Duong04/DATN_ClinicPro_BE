<?php

use App\Http\Controllers\Apis\V1\ConversationController;
use App\Http\Controllers\Apis\V1\MedicalHistoryController;
use App\Http\Controllers\Apis\V1\PatientController;
use App\Http\Controllers\Apis\V1\SpecialtyController;
use App\Http\Controllers\Apis\V1\UploadController;
use App\Http\Controllers\Apis\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\V1\DepartmentController;
use App\Http\Controllers\Apis\V1\ActionController;
use App\Http\Controllers\Apis\V1\AppointmentController;
use App\Http\Controllers\Apis\V1\AuthController;
use App\Http\Controllers\Apis\V1\FeedbackController;
use App\Http\Controllers\Apis\V1\MedicationController;
use App\Http\Controllers\Apis\V1\PackageCategoryController;
use App\Http\Controllers\Apis\V1\PermissionController;
use App\Http\Controllers\Apis\V1\RoleController;
use App\Http\Controllers\Apis\V1\PackageController;
use App\Http\Controllers\Apis\V1\PrescriptionController;
use PHPUnit\Framework\Attributes\Group;
use App\Http\Controllers\Apis\V1\ChatAIController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::controller(AuthController::class)->prefix('auth')
        ->group(function () {
            Route::post('/register', 'register');
            Route::post('/login', 'login');
            Route::post('/refresh', 'refresh');
            Route::post('/forgot-password', 'forgotPsw');
            Route::get('/forgot-password/{otp}', 'redirectResetPsw');
            Route::post('/reset-password', 'resetPsw');
            Route::get('/profile', 'profile')->middleware('jwt.auth');
            Route::put('/change-password', 'changePassword')->middleware('jwt.auth');
            Route::put('/profile/{id}', 'updateProfile')->middleware('jwt.auth');
            Route::post('/logout', 'logout')->middleware('jwt.auth');
        });

    Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);

    Route::controller(ActionController::class)->prefix('actions')->middleware('jwt.auth')
        ->group(function () {
            Route::get('/', 'paginate');
            Route::get('/{id}', 'show');
            Route::post('/', 'create');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

    Route::controller(PermissionController::class)->prefix('permissions')->middleware('jwt.auth')
        ->group(function () {
            Route::get('/', 'paginate');
            Route::get('/{id}', 'show');
            Route::post('/', 'create');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

    Route::controller(RoleController::class)->prefix('roles')->middleware('jwt.auth')
        ->group(function () {
            Route::get('/', 'paginate');
            Route::get('/{id}', 'show');
            Route::post('/', 'create');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

    Route::controller(PackageController::class)->prefix('packages')
        ->group(
            function () {
                Route::get('/', 'index');
                Route::post('/', 'store')->middleware('jwt.auth');
                Route::get('/{id}', 'show')->where('id', '[A-Fa-f0-9\-]{36}')->middleware('jwt.auth');
                Route::get('/categories/{id}', 'getByCategories');
                Route::get('/specialties', 'getBySpecialties');
                Route::get('/{slug}', 'slug')->where('slug', '[A-Za-z0-9\-]+');
                Route::put('/{id}', 'update')->middleware('jwt.auth');
                Route::delete('/{id}', 'destroy')->middleware('jwt.auth');
            }
        );
    Route::controller(AppointmentController::class)->prefix('appointments')
        ->group(
            function () {
                Route::get('/', 'index')->middleware('jwt.auth');
                Route::post('/', 'store');
                Route::get('/{id}', 'show')->middleware('jwt.auth');
                Route::get('/patient/{id}', 'findByIdPatient')->middleware('jwt.auth');
                Route::get('/send/{id}', 'update')->middleware('jwt.auth');
                Route::post('/{id}', 'cancel')->middleware('jwt.auth');
                Route::put('/{id}', 'complete')->middleware('jwt.auth');
                Route::post('/assign/{id}', 'assign')->middleware('jwt.auth');
                Route::delete('/{id}', 'destroy')->middleware('jwt.auth');
            }
        );

    Route::controller(DepartmentController::class)->prefix('departments')->middleware('jwt.auth')
        ->group(function () {
            Route::get('/', 'paginate');
            Route::get('/{id}', 'show');
            Route::post('/', 'create');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

    Route::controller(UserController::class)->prefix('users')->middleware('jwt.auth')
        ->group(function () {
            Route::get('/', 'paginate');
            Route::get('/check-department', 'getUserCheckDepartment');
            Route::get('/{id}', 'show');
            Route::get('/role/{id}', 'getByRole');
            Route::get('/{id}/specialty', 'getBySpecialtyId');
            Route::post('/', 'create');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

    Route::controller(MedicalHistoryController::class)->prefix('medical-histories')->middleware('jwt.auth')
        ->group(function () {
            Route::get('/', 'paginate');
            Route::get('/{id}', 'show');
            Route::get('/patient/{patient_id}', 'getByPatientId');
            Route::post('/', 'create');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

    Route::controller(PrescriptionController::class)->prefix('prescriptions')->middleware('jwt.auth')
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::get('/patient/{id}', 'listById');
            Route::get('/medical_history/{id}', 'getByMedicalHistory');
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

    Route::controller(PatientController::class)->prefix('patients')->middleware('jwt.auth')
        ->group(function () {
            Route::get('/', 'paginate');
            Route::get('/{id}', 'show');
            Route::post('/', 'create');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

    Route::get('specialties', [SpecialtyController::class, 'paginate']);
    Route::controller(SpecialtyController::class)->prefix('specialties')->middleware('jwt.auth')
        ->group(function () {
            Route::get('/{id}', 'show');
            Route::post('/', 'create');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

    Route::controller(FeedbackController::class)->prefix('feedbacks')->middleware('jwt.auth')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/package/{id}', 'findByIdPackage');
        Route::post('/', 'store');
        Route::delete('/{id}', 'destroy');
    });

    Route::get('categories/', [MedicationController::class, 'index'])->middleware('jwt.auth');
    Route::get('medications/', [MedicationController::class, 'show'])->middleware('jwt.auth');

    Route::controller(PackageCategoryController::class)->prefix('package-categories')
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show')->where('id', '[A-Fa-f0-9\-]{36}');
            Route::get('/{slug}', 'slug')->where('slug', '[A-Za-z0-9\-]+');
            Route::post('/', 'store')->middleware('jwt.auth');
            Route::put('/{id}', 'update')->middleware('jwt.auth');
            Route::delete('/{id}', 'destroy')->middleware('jwt.auth');
        });

    Route::controller(ChatAIController::class)->prefix('chat-AI')
        ->group(function () {
            Route::post('/', 'create');
        });

    Route::controller(UploadController::class)->prefix('upload')->group(function () {
        Route::post('/image', 'uploadImage');
        Route::post('/images', 'uploadImages');
        Route::post('/file', 'uploadFile');
        Route::post('/files', 'uploadFiles');
    });

    Route::controller(ChatAIController::class)->prefix('al')
        ->group(function () {
            Route::post('/chat', 'chatBox');
        });
    Route::prefix('users')->group(function () {
        Route::get('/{id}/conversations', [ConversationController::class, 'getByUserId']);
    });

    Route::controller(ConversationController::class)->prefix('conversations')
        ->group(function () {
            Route::get('/', 'paginate');
            Route::get('/{id}/{user_id}', 'show');
            Route::delete('/{id}', 'delete');
        });
});
