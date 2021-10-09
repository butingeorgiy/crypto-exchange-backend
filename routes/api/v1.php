<?php

use App\Http\Controllers\Api\V1\AuthenticationController;

Route::get('ping', ['App\Http\Controllers\Api\V1\PingController', 'checkPing']);

Route::prefix('authentication')->group(function () {
    Route::post('authenticate', [AuthenticationController::class, 'authenticate']);
});

Route::get('test', function () {
    $user = \App\Models\User::with('roles')->find(6);

    dd($user);
});