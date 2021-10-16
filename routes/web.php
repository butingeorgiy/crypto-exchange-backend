<?php


use App\Http\Controllers\Web\EmailVerificationController;

Route::get('email-verifications/verify', [EmailVerificationController::class, 'verify'])
    ->name('verify-email');