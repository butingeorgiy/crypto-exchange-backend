<?php

namespace App\Services\VerificationService\Exceptions;

use Exception;

class UnableCreateRequestException extends Exception
{
    public int $status = 422;

    public string $defaultMessage = 'Пользователь не может создать запрос на верификацию.';
}