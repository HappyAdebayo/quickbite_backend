<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController\AuthController;
use App\Http\Controllers\AdminController\CategoryController;
use App\Http\Controllers\AdminController\AdminOrderController;
use App\Http\Controllers\AdminController\MenuController;
use App\Http\Controllers\AdminController\DashboardController;

use App\Http\Controllers\UserController\CatalogController;
use App\Http\Controllers\UserController\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('admin')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('categories', [CategoryController::class, 'index']);
        Route::post('categories', [CategoryController::class, 'store']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        Route::get('/menus', [MenuController::class, 'index']);
        Route::post('/menus', [MenuController::class, 'store']);
        Route::put('/menus/{id}', [MenuController::class, 'update']);
        Route::delete('/menus/{id}', [MenuController::class, 'destroy']);
        Route::put('/menus/{id}/availability', [MenuController::class, 'toggleAvailability']);

        Route::get('/orders', [AdminOrderController::class, 'index']);
        Route::patch('/orders/{orderId}/status', [AdminOrderController::class, 'updateStatus']);
        
        Route::get('/dashboard', [DashboardController::class, 'index']);

    });
        
});
Route::prefix('user')->group(function () {
    Route::get('/categories', [CatalogController::class, 'categories']);
    Route::get('/menus', [CatalogController::class, 'menus']);
    Route::post('/orders', [OrderController::class, 'placeOrder']);
});