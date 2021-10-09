<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiBaseException extends Exception
{
    protected int $status = 500;

    protected string $defaultMessage = 'Internal Server Error.';

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => true,
            'message' => $this->getMessage() ?: $this->defaultMessage
        ], $this->status, options: JSON_UNESCAPED_UNICODE);
    }
}
