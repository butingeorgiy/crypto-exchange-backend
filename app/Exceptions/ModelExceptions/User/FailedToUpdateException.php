<?php

namespace App\Exceptions\ModelExceptions\User;

use Exception;

class FailedToUpdateException extends Exception
{
    public string $defaultMessage = 'Не удалось обновить пользователя.';
}