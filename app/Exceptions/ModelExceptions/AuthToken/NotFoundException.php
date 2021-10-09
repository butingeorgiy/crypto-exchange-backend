<?php

namespace App\Exceptions\ModelExceptions\AuthToken;

use App\Exceptions\ApiBaseException;

class NotFoundException extends ApiBaseException
{
    protected int $status = 404;

    protected string $defaultMessage = 'Токен аутентификации не найден.';
}