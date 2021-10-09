<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ApiValidationException extends ValidationException
{
    /**
     * Render exception.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => true,
            'message' => $this->validator->errors()->first() ?? 'The given data was invalid.'
        ], $this->status, options: JSON_UNESCAPED_UNICODE);
    }
}
