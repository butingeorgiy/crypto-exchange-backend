<?php

namespace App\Services\SmsConfirmationService\Drivers;

use App\Models\SmsConfirmation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FakeSmsDriver implements SmsConfirmationDriverInterface
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
    public function send(string $phoneNumber): void
    {
        Log::debug(sprintf('Sms code on %s: %s', $phoneNumber, $this->code));

        $this->storeCodeInDatabase($phoneNumber);
    }

    /**
     * @inheritDoc
     */
    public function verified(string $phoneNumber, string $code): bool
    {
        return SmsConfirmation::valid()->where([
            ['code', $code],
            ['phone_number', $phoneNumber]
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

            if (!SmsConfirmation::where('code', $code)->exists()) break;
        }

        $this->code = $code;
    }

    /**
     * Store confirmation sms code in database.
     *
     * @param string $phoneNumber
     */
    protected function storeCodeInDatabase(string $phoneNumber): void
    {
        SmsConfirmation::create([
            'code' => $this->code,
            'phone_number' => $phoneNumber,
            'expired_at' => now()->addMinutes(5)
        ]);
    }
}