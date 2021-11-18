<?php

namespace App\Services\UserService\Exceptions;

use Exception;

class EmptyUpdatesException extends Exception
{
    public string $defaultMessage = 'Необходимо изменить хотя бы одно поле (E-mail или Пароль).';
}