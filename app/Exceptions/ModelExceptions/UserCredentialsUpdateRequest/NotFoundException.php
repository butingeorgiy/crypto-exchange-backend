<?php

namespace App\Exceptions\ModelExceptions\UserCredentialsUpdateRequest;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class NotFoundException extends Exception implements HttpExceptionInterface
{
    protected $message = 'Запрос на изменение данных Безопасности не найден.';

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