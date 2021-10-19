<?php

namespace App\Exceptions\ModelExceptions\User;

use Exception;

class WrongPasswordException extends Exception
{
    public int $status = 401;

    public string $defaultMessage = 'Неверный пароль.';
}