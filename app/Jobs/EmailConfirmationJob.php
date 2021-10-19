<?php

namespace App\Jobs;

use App\Services\EmailConfirmationService\Drivers\EmailConfirmationDriverInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EmailConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $emailAddress;

    /**
     * Create a new job instance.
     *
     * @param string $emailAddress
     *
     * @return void
     */
    public function __construct(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EmailConfirmationDriverInterface $confirmationDriver)
    {
        $confirmationDriver->send($this->emailAddress);
    }
}
