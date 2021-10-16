<?php

namespace App\Jobs;

use App\Mail\EmailVerification;
use App\Models\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailVerificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * User instance.
     *
     * @var User
     */
    protected User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailVerification = EmailVerificationRequest::prepareUnique();
        $emailVerificationUrl = route('verify-email', [
            'uuid' => $emailVerification->id,
            'salt' => $emailVerification->salt
        ]);

        $this->user->emailVerifications()->save($emailVerification);

        Mail::to($this->user->email)->send(new EmailVerification($emailVerificationUrl));
    }
}
