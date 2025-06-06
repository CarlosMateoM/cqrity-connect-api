<?php

use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\AccessRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsUserEnabledMiddleware;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware([
        'auth:sanctum',
        IsUserEnabledMiddleware::class
    ])->group(function () {

        Route::get('/user', function (Request $request) {

            $user = $request->user();

            $user->load('roles');

            return  ($user);
        });

        Route::apiResource('users', UserController::class);
        Route::apiResource('access-logs', AccessLogController::class);
        Route::apiResource('access-requests', AccessRequestController::class);

        Route::post('logout', [AuthController::class, 'logout']);
    });
});
