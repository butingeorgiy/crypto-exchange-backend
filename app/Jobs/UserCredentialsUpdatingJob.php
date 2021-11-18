<?php

namespace App\Jobs;

use App\Mail\UserCredentialsUpdating as UserCredentialsUpdatingMail;
use App\Models\UserCredentialsUpdateRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class UserCredentialsUpdatingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected UserCredentialsUpdateRequest $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserCredentialsUpdateRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $confirmationUrl = route('apply-credentials-updates', [
            'id' => $this->request->id,
            'salt' => $this->request->salt
        ]);

        Mail::to($this->request->user->email)->send(new UserCredentialsUpdatingMail($confirmationUrl));
    }
}
