<?php

namespace App\Exceptions\ModelExceptions\ExchangeEntity;

use Exception;

class NotFoundException extends Exception
{
    public int $status = 404;

    public string $defaultMessage = 'Позиция обмена не найдена.';
}