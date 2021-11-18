<?php

namespace App\Exceptions\ModelExceptions\UserCredentialsUpdateRequest;

use Exception;

class NotFoundException extends Exception
{
    public int $status = 404;

    public string $defaultMessage = 'Запрос на изменение данных Безопасности не найден.';
}