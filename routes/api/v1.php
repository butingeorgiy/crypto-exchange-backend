<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Controllers\Api\V1\EmailConfirmationController;
use App\Http\Controllers\Api\V1\GeneralController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\VerificationController;
use Illuminate\Support\Facades\Route;

Route::get('general', [GeneralController::class, 'getServiceData']);

Route::prefix('authentication')->group(function () {
    Route::post('authenticate', [AuthenticationController::class, 'authenticate'])
        ->middleware('guest:sanctum');

    Route::post('register', [AuthenticationController::class, 'register']);
});

Route::post('email-confirmation/send', [EmailConfirmationController::class, 'send']);

Route::prefix('users')->group(function () {
    Route::prefix('current')->group(function () {
        Route::get('', [UserController::class, 'current'])
            ->middleware(['auth:sanctum', 'ability:regular-user']);

        Route::post('update', [UserController::class, 'updateCurrent'])
            ->middleware(['auth:sanctum', 'ability:regular-user']);

        Route::post('update-credentials', [UserController::class, 'updateCredentials'])
            ->middleware(['auth:sanctum', 'ability:regular-user']);
    });
});

Route::prefix('verification')->group(function () {
    Route::post('create-request', [VerificationController::class, 'create'])
        ->middleware(['auth:sanctum', 'ability:regular-user']);
});

Route::prefix('transactions')->group(function () {
    Route::post('prepare', [TransactionController::class, 'prepare']);
    Route::post('create', [TransactionController::class, 'create']);
});
