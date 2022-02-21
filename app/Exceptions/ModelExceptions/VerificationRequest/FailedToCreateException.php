<?php

namespace App\Exceptions\ModelExceptions\VerificationRequest;

use Exception;

class FailedToCreateException extends Exception
{
    protected $message = 'Не удалось создать запрос на верификацию.';
}