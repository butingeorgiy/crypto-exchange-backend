<?php

namespace App\Services\AuthenticationService\Exceptions;

use Exception;

class NonEmailVerifiedUserException extends Exception
{
    public int $status = 401;

    public string $defaultMessage = 'Пользователь не подтвердил E-mail.';
}