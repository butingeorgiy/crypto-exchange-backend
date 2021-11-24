<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Verification\CreateRequest;
use App\Services\VerificationService\Client as VerificationService;
use App\Services\VerificationService\Exceptions\FailedToParseToAttachmentDtoException;
use Exception;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    /**
     * Apply verification request.
     *
     * @param CreateRequest $request
     *
     * @return JsonResponse
     *
     * @throws FailedToParseToAttachmentDtoException
     * @throws Exception
     */
    public function create(CreateRequest $request): JsonResponse
    {
        VerificationService::builder()
            ->parseFromHttpRequest($request)
            ->save();

        return response()->json([
            'success' => true,
            'message' => 'Ваш запрос на верификацию успешно отправлен. Ожидайте решение модератора...'
        ]);
    }
}
