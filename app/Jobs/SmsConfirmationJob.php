<?php

namespace App\Jobs;

use App\Services\SmsConfirmationService\Drivers\SmsConfirmationDriverInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SmsConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $phoneNumber;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SmsConfirmationDriverInterface $confirmationDriver)
    {
        $confirmationDriver->send($this->phoneNumber);
    }
}
