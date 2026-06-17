<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\StoresController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,1');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:3,1');

    Route::middleware('auth:api')->group(function () {
        Route::controller(ProductsController::class)
            ->group(function () {
                Route::get('/products/{storeId}', 'index');
                Route::post('/products', 'store');
                Route::put('/products/{id}', 'update');
                Route::delete('/products/{id}', 'destroy');
            });

        Route::resource('users', UsersController::class);

        Route::get('/orders/trashed', [OrdersController::class, 'trashed']);
        Route::resource('orders', OrdersController::class);

        Route::resource('categories', CategoriesController::class);

        Route::resource('stores', StoresController::class);

        Route::get('notifications', [NotificationsController::class, 'unReadNotifications']);
        Route::patch('notifications/mark-all-as-read', [NotificationsController::class, 'markAllAsRead']);
        Route::patch('notifications/{id}', [NotificationsController::class, 'markAsRead']);

        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
