<?php

namespace App\Exceptions\ModelExceptions\UserCredentialsUpdateRequest;

use Exception;

class FailedToCreateException extends Exception
{
    public string $defaultMessage = 'Failed to create user credentials update request.';
}