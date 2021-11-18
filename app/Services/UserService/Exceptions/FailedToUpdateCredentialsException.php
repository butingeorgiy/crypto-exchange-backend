<?php

namespace App\Services\UserService\Exceptions;

use Exception;

class FailedToUpdateCredentialsException extends Exception
{
    public string $defaultMessage = 'Failed to update user\'s credentials.';
}