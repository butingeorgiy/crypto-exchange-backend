<?php

namespace App\Services\AuthenticationService\Exceptions;

use Exception;

class FailedToAttachTokenException extends Exception
{
    public int $status = 500;

    public string $defaultMessage = 'Failed to attach authentication token.';
}