<?php

namespace App\Exceptions\ModelExceptions\User;

use App\Exceptions\ApiBaseException;

class WrongPasswordException extends ApiBaseException
{
    protected int $status = 403;

    protected string $defaultMessage = 'Неверный пароль.';
}