<?php

namespace App\Exceptions\ModelExceptions\User;

use Exception;

class FailedToUpdateException extends Exception
{
    protected $message = 'Не удалось обновить пользователя.';
}