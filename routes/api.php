<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesApiController;
use App\Http\Controllers\Api\NotificationsApiController;
use App\Http\Controllers\Api\OrdersApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\StoresApiController;
use App\Http\Controllers\Api\UsersApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;









Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::controller(ProductApiController::class)
        ->group(function () {
            Route::get('/products', 'index');
            Route::post('/products', 'store');
            Route::put('/products/{id}', 'update');
            Route::delete('/products/{id}', 'destroy');
        });

        Route::resource('users', UsersApiController::class);
        Route::resource('orders', OrdersApiController::class);
        Route::get('/orders/trashed', [OrdersApiController::class, 'trashed']);

        Route::resource('categories' , CategoriesApiController::class);

        Route::resource('stores' , StoresApiController::class);

        Route::resource('notifications' , NotificationsApiController::class);
    });
});
