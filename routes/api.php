<?php

use App\Http\Controllers\Api\V1\ApplicationController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\IncubatorController;
use App\Http\Controllers\Api\V1\MeController;
use App\Http\Controllers\Api\V1\SectorController;
use App\Http\Controllers\Api\V1\StartupController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public
    Route::get('/incubators', [IncubatorController::class, 'index']);
    Route::get('/incubators/{slug}', [IncubatorController::class, 'show']);
    Route::get('/startups', [StartupController::class, 'index']);
    Route::get('/startups/{slug}', [StartupController::class, 'show']);
    Route::get('/sectors', [SectorController::class, 'index']);

    // Auth
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Authenticated
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/me', [MeController::class, 'show']);
        Route::put('/me/profile', [MeController::class, 'update']);

        Route::get('/applications', [ApplicationController::class, 'index']);
        Route::post('/applications', [ApplicationController::class, 'store']);
        Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus']);
    });
});
