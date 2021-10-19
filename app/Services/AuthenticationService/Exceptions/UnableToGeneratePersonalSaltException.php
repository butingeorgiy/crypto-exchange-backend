<?php

namespace App\Services\AuthenticationService\Exceptions;

use Exception;

class UnableToGeneratePersonalSaltException extends Exception
{
    public int $status = 500;

    public string $defaultMessage = 'Unable to generate personal salt. ' .
    'User model must have "password" and "email" properties.';
}
