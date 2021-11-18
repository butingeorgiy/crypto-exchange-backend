<?php

namespace App\Services\UserService;

use JetBrains\PhpStorm\Pure;

class Client
{
    /**
     * Return user's credentials updater service.
     *
     * @return UserCredentialsUpdater
     */
    #[Pure]
    public static function credentialsUpdater(): UserCredentialsUpdater
    {
        return new UserCredentialsUpdater;
    }
}