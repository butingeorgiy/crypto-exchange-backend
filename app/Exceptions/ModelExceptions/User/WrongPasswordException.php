<?php

namespace App\Exceptions\ModelExceptions\User;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class WrongPasswordException extends Exception implements HttpExceptionInterface
{
    protected $message = 'Неверный пароль.';

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return 401;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return [];
    }
}