<?php

namespace App\Exceptions\ModelExceptions\ExchangeEntity;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class NotFoundException extends Exception implements HttpExceptionInterface
{
    protected $message = 'Позиция обмена не найдена.';

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return 404;
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