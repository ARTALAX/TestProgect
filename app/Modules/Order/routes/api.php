<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::prefix('orders')->middleware(['auth:api', 'role:user'])->group(callback: function (): void {
    Route::get('/', [OrderController::class, 'index']);             // Список заказов пользователя
    Route::get('/{order}', [OrderController::class, 'show']);       // Просмотр конкретного заказа
    Route::post('/', [OrderController::class, 'store']);            // Создать заказ из корзины
    Route::patch('/{order}/status', [OrderController::class, 'updateStatus']); // Обновить статус (только админ/внутри)
    Route::delete('/{order}', [OrderController::class, 'cancel']);  // Отменить заказ
});
