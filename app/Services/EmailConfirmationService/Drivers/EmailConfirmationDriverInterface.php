<?php

namespace App\Services\EmailConfirmationService\Drivers;

interface EmailConfirmationDriverInterface
{
    /**
     * Send confirmation code to email address.
     *
     * @param string $emailAddress
     */
    public function send(string $emailAddress): void;

    /**
     * Confirm that code is right.
     *
     * @param string $emailAddress
     * @param string $code
     *
     * @return bool
     */
    public function verified(string $emailAddress, string $code): bool;
}