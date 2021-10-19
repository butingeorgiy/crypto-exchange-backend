<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\SmsConfirmationJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmsConfirmationController extends Controller
{
    /**
     * Send confirmation code by sms.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'phone_number' => ['required', 'regex:/^(\d{1,4})(\d{3})(\d{3})(\d{4})$/']
        ]);

        SmsConfirmationJob::dispatch($request->input('phone_number'))->delay(now()->addSecond());

        return response()->json([
            'success' => true
        ], options: JSON_UNESCAPED_UNICODE);
    }
}
