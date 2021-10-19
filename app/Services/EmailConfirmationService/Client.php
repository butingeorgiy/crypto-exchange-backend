<?php

namespace App\Services\EmailConfirmationService;

use App\Models\EmailConfirmation;
use App\Services\EmailConfirmationService\Drivers\EmailConfirmationDriverInterface;

class Client
{
    /**
     * Driver instance.
     *
     * @var EmailConfirmationDriverInterface
     */
    protected EmailConfirmationDriverInterface $driver;

    /**
     * Last checked code.
     *
     * @var string
     */
    protected string $lastCheckedCode;

    /**
     * @param EmailConfirmationDriverInterface $driver
     */
    public function __construct(EmailConfirmationDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Send confirmation code to email address.
     *
     * @param string $emailAddress
     */
    public function send(string $emailAddress): void
    {
        $this->driver->send($emailAddress);
    }

    /**
     * Confirm that code is right.
     *
     * @param string $emailAddress
     * @param string $code
     *
     * @return bool
     */
    public function verified(string $emailAddress, string $code): bool
    {
        $this->lastCheckedCode = $code;

        return $this->driver->verified($emailAddress, $code);
    }

    /**
     * Delete last email confirmation from database.
     */
    public function deleteLastFromDatabase(): void
    {
        if (isset($this->lastCheckedCode)) {
            EmailConfirmation::where('code', $this->lastCheckedCode)->delete();
        }
    }
}