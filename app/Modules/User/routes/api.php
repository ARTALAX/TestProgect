<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Enums\UserRole;
use Modules\User\Http\Controllers\AdminUserController;
use Modules\User\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api', 'role:'.UserRole::ADMIN->value]], function (): void {
    Route::get('users', [AdminUserController::class, 'index']);
    Route::patch('users/{user}/role', [AdminUserController::class, 'updateRole']);
});
