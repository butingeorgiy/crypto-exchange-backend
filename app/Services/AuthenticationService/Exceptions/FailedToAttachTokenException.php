<?php

namespace App\Services\AuthenticationService\Exceptions;

use App\Exceptions\ApiBaseException;

class FailedToAttachTokenException extends ApiBaseException
{
    protected string $defaultMessage = 'Failed to attach authentication token.';
}