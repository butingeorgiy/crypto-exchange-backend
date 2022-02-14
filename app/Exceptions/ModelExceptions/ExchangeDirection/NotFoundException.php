<?php

namespace App\Exceptions\ModelExceptions\ExchangeDirection;

use Exception;

class NotFoundException extends Exception
{
    public int $status = 404;

    public string $defaultMessage = 'Направление обмена не найдено.';
}