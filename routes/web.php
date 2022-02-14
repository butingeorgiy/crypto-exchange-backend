<?php

use App\Http\Controllers\Web\CredentialsController;
use App\Http\Controllers\Web\EmailVerificationController;
use App\Services\TransactionService\Client as TransactionServiceClient;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

Route::get('email-verifications/verify', [EmailVerificationController::class, 'verify'])
    ->name('verify-email');

Route::get('credentials/apply', [CredentialsController::class, 'apply'])
    ->name('apply-credentials-updates');

Route::prefix('admin')->group(function () {
    Route::get('logs', [LogViewerController::class, 'index']);
});

Route::get('test', function () {
    $validator = TransactionServiceClient::validator();

    dump($validator->canUserPrepareTransaction(1, false, 20000, userId: 7));
});