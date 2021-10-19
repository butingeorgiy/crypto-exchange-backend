<?php

namespace App\Services\EmailConfirmationService\Drivers;

use App\Mail\EmailConfirmation as EmailConfirmationMail;
use App\Models\EmailConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailConfirmationDriver implements EmailConfirmationDriverInterface
{
    /**
     * Secure code.
     *
     * @var string
     */
    protected string $code;

    public function __construct()
    {
        $this->generateCode();
    }

    /**
     * @inheritDoc
     */
    public function send(string $emailAddress): void
    {
        Mail::to($emailAddress)->send(new EmailConfirmationMail($this->code));

        $this->storeCodeInDatabase($emailAddress);
    }

    /**
     * @inheritDoc
     */
    public function verified(string $emailAddress, string $code): bool
    {
        return EmailConfirmation::valid()->where([
            ['code', $code],
            ['email_address', $emailAddress]
        ])->exists();
    }

    /**
     * Generate random code.
     *
     * @return void
     */
    protected function generateCode(): void
    {
        while (true) {
            $code = Str::random(6);

            if (!EmailConfirmation::where('code', $code)->exists()) break;
        }

       $this->code = $code;
    }

    /**
     * Store confirmation email code in database.
     *
     * @param string $emailAddress
     */
    protected function storeCodeInDatabase(string $emailAddress): void
    {
        EmailConfirmation::create([
            'code' => $this->code,
            'email_address' => $emailAddress,
            'expired_at' => now()->addMinutes(5)
        ]);
    }
}