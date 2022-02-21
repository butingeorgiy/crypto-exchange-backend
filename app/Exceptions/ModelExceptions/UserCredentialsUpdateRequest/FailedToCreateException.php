<?php

namespace App\Exceptions\ModelExceptions\UserCredentialsUpdateRequest;

use Exception;

class FailedToCreateException extends Exception
{
    protected $message = 'Failed to create user credentials update request.';
}