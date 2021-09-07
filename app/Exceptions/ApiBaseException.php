<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiBaseException extends Exception
{
    protected int $statusCode = 500;

    final public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage()
        ], $this->statusCode, options: JSON_UNESCAPED_UNICODE);
    }
}
