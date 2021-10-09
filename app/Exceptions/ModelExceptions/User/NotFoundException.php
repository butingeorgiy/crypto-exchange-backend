<?php

namespace App\Exceptions\ModelExceptions\User;

use App\Exceptions\ApiBaseException;

class NotFoundException extends ApiBaseException
{
    protected int $status = 404;

    protected string $defaultMessage = 'Пользователь не найден.';
}