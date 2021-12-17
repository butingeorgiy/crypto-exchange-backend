<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Controllers\Api\V1\EmailConfirmationController;
use App\Http\Controllers\Api\V1\ExchangeController;
use App\Http\Controllers\Api\V1\PingController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\VerificationController;

Route::get('ping', [PingController::class, 'checkPing']);

Route::prefix('authentication')->group(function () {
    Route::post('authenticate', [AuthenticationController::class, 'authenticate']);
    Route::post('register', [AuthenticationController::class, 'register']);
});

Route::post('email-confirmation/send', [EmailConfirmationController::class, 'send']);

Route::prefix('users')->group(function () {
    Route::prefix('current')->group(function () {
        Route::get('', [UserController::class, 'current'])
            ->middleware('auth');

        Route::post('update', [UserController::class, 'updateCurrent'])
            ->middleware('auth:regular-user');

        Route::post('update-credentials', [UserController::class, 'updateCredentials'])
            ->middleware('auth:regular-user');
    });
});

Route::prefix('verification')->group(function () {
    Route::post('create-request', [VerificationController::class, 'create'])
        ->middleware('auth:regular-user');
});

Route::get('exchange-directions', [ExchangeController::class, 'directions']);