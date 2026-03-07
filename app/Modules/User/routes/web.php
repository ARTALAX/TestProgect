<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

Route::middleware(['auth', 'verified'])->group(callback: function (): void {
    Route::resource('users', UserController::class)->names(names: 'user');
});
