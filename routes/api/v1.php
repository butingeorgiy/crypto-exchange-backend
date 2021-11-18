<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Controllers\Api\V1\EmailConfirmationController;
use App\Http\Controllers\Api\V1\SmsConfirmationController;
use App\Http\Controllers\Api\V1\UserController;

Route::get('ping', ['App\Http\Controllers\Api\V1\PingController', 'checkPing']);

Route::prefix('authentication')->group(function () {
    Route::post('authenticate', [AuthenticationController::class, 'authenticate']);
    Route::post('register', [AuthenticationController::class, 'register']);
});

Route::post('sms-confirmation/send', [SmsConfirmationController::class, 'send']);
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