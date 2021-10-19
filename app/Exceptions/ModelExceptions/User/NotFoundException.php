<?php

namespace App\Exceptions\ModelExceptions\User;

use Exception;

class NotFoundException extends Exception
{
    public int $status = 404;

    public string $defaultMessage = 'Пользователь не найден.';
}