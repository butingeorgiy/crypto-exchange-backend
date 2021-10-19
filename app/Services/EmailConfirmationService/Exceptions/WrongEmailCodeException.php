<?php

namespace App\Services\EmailConfirmationService\Exceptions;

use Exception;

class WrongEmailCodeException extends Exception
{
    public int $status = 422;

    public string $defaultMessage = 'Неверный E-mail код.';
}