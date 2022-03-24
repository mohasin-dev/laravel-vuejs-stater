<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
 
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

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
        Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
        Route::post('me', [App\Http\Controllers\AuthController::class, 'me'])->name('me');
        Route::post('refresh', 'AuthController@refresh');
    });

    Route::group(['middleware' => 'jwt.auth', 'namespace' => 'App\Http\Controllers'], function () {
        Route::get('/sidebar-permissions', [DashboardController::class, 'index']);
        Route::resource('/users', UserController::class);
        Route::resource('/roles', RoleController::class);
        Route::resource('/simple-crud', SimpleCrudController::class);
    });
