<?php

use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\AccessRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsUserEnabledMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login'])
    ->middleware(IsUserEnabledMiddleware::class);


Route::prefix('v1')

    ->middleware([
        'auth:sanctum',
        IsUserEnabledMiddleware::class
    ])
    ->group(function () {

        Route::apiResource('users', UserController::class);
        Route::apiResource('access-logs', AccessLogController::class);
        Route::apiResource('access-requests', AccessRequestController::class);
        
        Route::post('logout', [AuthController::class, 'logout']);
    });
