<?php

use App\Http\Controllers\Web\CredentialsController;
use App\Http\Controllers\Web\EmailVerificationController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

Route::get('email-verifications/verify', [EmailVerificationController::class, 'verify'])
    ->name('verify-email');

Route::get('credentials/apply', [CredentialsController::class, 'apply'])
    ->name('apply-credentials-updates');

Route::prefix('admin')->group(function () {
    Route::get('logs', [LogViewerController::class, 'index']);
});