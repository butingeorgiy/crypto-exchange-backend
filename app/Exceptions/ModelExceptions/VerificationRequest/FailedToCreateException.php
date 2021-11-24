<?php

namespace App\Exceptions\ModelExceptions\VerificationRequest;

use Exception;

class FailedToCreateException extends Exception
{
    public string $defaultMessage = 'Не удалось создать запрос на верификацию.';
}