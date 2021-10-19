<?php

namespace App\Services\SmsConfirmationService;

use App\Models\SmsConfirmation;
use App\Services\SmsConfirmationService\Drivers\SmsConfirmationDriverInterface;

class Client
{
    /**
     * Sms driver instance.
     *
     * @var SmsConfirmationDriverInterface
     */
    protected SmsConfirmationDriverInterface $driver;

    /**
     * Last checked code.
     *
     * @var string
     */
    protected string $lastCheckedCode;

    /**
     * @param SmsConfirmationDriverInterface $driver
     */
    public function __construct(SmsConfirmationDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Send confirmation code on phone number.
     *
     * @param string $phoneNumber
     */
    public function send(string $phoneNumber): void
    {
        $this->driver->send($phoneNumber);
    }

    /**
     * Confirm that code is right.
     *
     * @param string $phoneNumber
     * @param string $code
     *
     * @return bool
     */
    public function verified(string $phoneNumber, string $code): bool
    {
        $this->lastCheckedCode = $code;

        return $this->driver->verified($phoneNumber, $code);
    }

    /**
     * Delete last email confirmation from database.
     */
    public function deleteLastFromDatabase(): void
    {
        if (isset($this->lastCheckedCode)) {
            SmsConfirmation::where('code', $this->lastCheckedCode)->delete();
        }
    }
}