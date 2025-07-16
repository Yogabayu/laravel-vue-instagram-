<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\FindingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('logout', [AuthController::class, 'logout']);
        // route admin


        //////// Route User \\\\\\\\\
        Route::group(['middleware' => ['permission:create-users,view-users,update-users,delete-users']], function () {
            Route::get('users', [UserController::class, 'getAllUser']);
            Route::get('roles', [UserController::class, 'getAllrole']);
            Route::delete('users/{id}', [UserController::class, 'deleteUser']);
            Route::post('users', [UserController::class, 'addUser']);
            Route::get('userPermission/{id}', [UserController::class, 'userPermission']);
            Route::post('updateUser', [UserController::class, 'updateUser']);
        });
    });
});
