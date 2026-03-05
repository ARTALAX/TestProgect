<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;

//Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//
//});
//Route::apiResource('products', ProductController::class)->names('product');

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
});
