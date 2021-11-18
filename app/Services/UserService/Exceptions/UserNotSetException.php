<?php

namespace App\Services\UserService\Exceptions;

use Exception;

class UserNotSetException extends Exception
{
    public string $defaultMessage = 'Unable to create update request, because user has not been set.';
}