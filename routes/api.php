<?php

use App\Http\Controllers\Api\OrdersApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\UsersApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;





Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


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
