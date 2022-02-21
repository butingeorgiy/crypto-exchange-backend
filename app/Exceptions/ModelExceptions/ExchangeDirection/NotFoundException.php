<?php

namespace App\Exceptions\ModelExceptions\ExchangeDirection;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class NotFoundException extends Exception implements HttpExceptionInterface
{
    protected $message = 'Направление обмена не найдено.';

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