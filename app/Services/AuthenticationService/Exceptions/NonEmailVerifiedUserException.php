<?php

namespace App\Services\AuthenticationService\Exceptions;

use App\Exceptions\ApiBaseException;

class NonEmailVerifiedUserException extends ApiBaseException
{
    protected int $status = 422;

    protected string $defaultMessage = 'Пользователь не подтвердил E-mail.';
}