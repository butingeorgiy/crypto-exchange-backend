<?php


use App\Http\Controllers\Web\EmailVerificationController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

Route::get('email-verifications/verify', [EmailVerificationController::class, 'verify'])
    ->name('verify-email');

Route::prefix('admin')->group(function () {
    Route::get('logs', [LogViewerController::class, 'index'])->middleware('auth.basic');
});