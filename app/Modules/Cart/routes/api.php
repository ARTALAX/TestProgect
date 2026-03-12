<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;

Route::prefix('cart')->middleware(['auth:api', 'role:user'])->group(callback: function (): void {
    Route::get('/', [CartController::class, 'show']);
    Route::post('add', [CartController::class, 'addItem']);
    Route::put('update', [CartController::class, 'updateItem']);
    Route::delete('delete', [CartController::class, 'deleteItem']);
});
