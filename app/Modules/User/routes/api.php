<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Http\Controllers\UserController;

//Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//
//});
Route::apiResource('users', UserController::class);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
