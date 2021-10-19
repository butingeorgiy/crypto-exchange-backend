<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\EmailConfirmationJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailConfirmationController extends Controller
{
    /**
     * Send confirmation code by mail.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|max:255'
        ]);

        EmailConfirmationJob::dispatch($request->input('email'))->delay(now()->addSecond());

        return response()->json([
            'success' => true
        ], options: JSON_UNESCAPED_UNICODE);
    }
}
