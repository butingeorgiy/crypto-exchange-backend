<?php

namespace App\Exceptions\ModelExceptions\AuthToken;

use Exception;

class NotFoundException extends Exception
{
    public int $status = 404;

    public string $defaultMessage = 'Токен аутентификации не найден.';
}