<?php

namespace App\Services\UserService\Exceptions;

use Exception;

class CredentialsRequestValidationFailedException extends Exception
{
    public int $status = 422;

    public string $defaultMessage = 'Validation before user credentials update request failed.';
}