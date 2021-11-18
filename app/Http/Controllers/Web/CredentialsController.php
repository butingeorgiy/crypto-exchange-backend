<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\ModelExceptions\UserCredentialsUpdateRequest\NotFoundException as UpdatesRequestNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\UserService\Client as UserService;
use App\Services\UserService\Exceptions\FailedToUpdateCredentialsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CredentialsController extends Controller
{
    /**
     * Apply user's credentials updates.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws UpdatesRequestNotFoundException
     * @throws FailedToUpdateCredentialsException
     */
    public function apply(Request $request): JsonResponse
    {
        UserService::credentialsUpdater()
            ->apply($request->input('id', ''), $request->input('salt', ''));

        return response()->json([
            'success' => true,
            'message' => 'Данные безопасности успешно изменены.'
        ]);
    }
}
