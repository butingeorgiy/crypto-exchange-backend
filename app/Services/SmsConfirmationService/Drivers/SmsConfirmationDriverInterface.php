<?php

namespace App\Services\SmsConfirmationService\Drivers;

interface SmsConfirmationDriverInterface
{
    /**
     * Send confirmation code to email address.
     *
     * @param string $phoneNumber
     */
    public function send(string $phoneNumber): void;

    /**
     * Confirm that code is right.
     *
     * @param string $phoneNumber
     * @param string $code
     *
     * @return bool
     */
    public function verified(string $phoneNumber, string $code): bool;
}