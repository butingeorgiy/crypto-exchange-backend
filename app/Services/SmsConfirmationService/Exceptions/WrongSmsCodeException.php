<?php

namespace App\Services\SmsConfirmationService\Exceptions;

use Exception;

class WrongSmsCodeException extends Exception
{
    public int $status = 422;

    public string $defaultMessage = 'Неверный SMS код.';
}