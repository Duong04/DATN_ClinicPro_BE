<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\V1\ActionController;
use App\Http\Controllers\Apis\V1\AuthController;
use App\Http\Controllers\Apis\V1\PermissionController;
use App\Http\Controllers\Apis\V1\RoleController;
use App\Http\Controllers\Apis\V1\PackageController;
use PHPUnit\Framework\Attributes\Group;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::controller(AuthController::class)->prefix('auth')
        ->group(function () {
            Route::post('/register', 'register');
            Route::post('/login', 'login');
            Route::post('/refresh', 'refresh');
            Route::get('/profile', 'profile')->middleware('jwt.auth');
            Route::put('/profile/{id}', 'updateProfile')->middleware('jwt.auth');
            Route::post('/logout', 'logout')->middleware('jwt.auth');
        });

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

    Route::controller(PackageController::class)->prefix('packages')->middleware('jwt.auth')
        ->group(
            function () {
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::get('/{id}', 'show')->where('id', '[0-9]+');
                Route::get('/{slug}', 'slug')->where('slug', '[A-Za-z0-9\-]+');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            }
        );
});
