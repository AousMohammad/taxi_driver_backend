<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/account', [AuthController::class, 'account']);

    Route::group(['middleware' => 'isDriver'], function () {

        Route::post('/customer/order/{id}', [CustomerController::class, 'show']);
        Route::get('/driver/list/orders', [DriverController::class, 'getOrders']);
        Route::get('/driver/travels', [DriverController::class, 'get_travel']);
        Route::delete('/driver/travels/{id}', [DriverController::class, 'delete_travels']);
        Route::get('/driver/free', [DriverController::class, 'getFree']);
        Route::post('/driver/free', [DriverController::class, 'setFree']);
    });

    Route::group(['middleware' => 'isCustomer'], function () {

        Route::get('/driver/{id}', [DriverController::class, 'show']);
        Route::get('/customer/get_nearly_cars', [CustomerController::class, 'getNearlyCars']);
        Route::post('/customer/order_taxi', [CustomerController::class, 'orderTaxi']);
    });
});
