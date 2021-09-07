<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

class PingController extends Controller
{
    /**
     * Check server ping.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function checkPing(): JsonResponse
    {
        return response()->json([
            'success' => true
        ], options: JSON_UNESCAPED_UNICODE);
    }
}
