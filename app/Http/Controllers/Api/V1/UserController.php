<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ModelExceptions\User\FailedToUpdateException as FailedToUpdateUserException;
use App\Exceptions\ModelExceptions\User\NotFoundException as UserNotFoundException;
use App\Exceptions\ModelExceptions\UserCredentialsUpdateRequest\FailedToCreateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateCredentialsRequest;
use App\Http\Requests\User\UpdateCurrentRequest;
use App\Models\User;
use App\Services\AuthenticationService\Client as AuthenticationClient;
use App\Services\UserService\Client as UserService;
use App\Services\UserService\Exceptions\CredentialsRequestValidationFailedException;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Get current authenticated user.
     *
     * @return JsonResponse
     *
     * @throws UserNotFoundException
     */
    public function current(): JsonResponse
    {
        $userId = app(AuthenticationClient::class)->currentUserId();

        $user = User::select([
            'first_name',
            'last_name',
            'middle_name',
            'phone_number',
            'email',
            'ref_code',
            'is_verified'
        ])->find($userId) ?: throw new UserNotFoundException;

        return response()->json($user, options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update current authenticated user.
     *
     * @param UpdateCurrentRequest $request
     *
     * @return JsonResponse
     *
     * @throws FailedToUpdateUserException
     */
    public function updateCurrent(UpdateCurrentRequest $request): JsonResponse
    {
        $user = app(AuthenticationClient::class)->currentUser();

        if (
            $request->has('first_name') &&
            $user->first_name !== $request->input('first_name')
        ) {
            $user->first_name = $request->input('first_name');
        }

        if (
            $request->has('last_name') &&
            $user->last_name !== $request->input('last_name')
        ) {
            $user->last_name = $request->input('last_name');
        }

        if (
            $request->has('middle_name') &&
            $user->middle_name !== $request->input('middle_name')
        ) {
            $user->middle_name = $request->input('middle_name');
        }

        if (
            $request->has('phone_number') &&
            $user->phone_number !== $request->input('phone_number')
        ) {
            $user->phone_number = $request->input('phone_number');
        }

        $user->save() ?: throw new FailedToUpdateUserException;

        return response()->json([
            'success' => true,
            'message' => 'Изменения успешно сохранены.'
        ], options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update user's credentials.
     *
     * @param UpdateCredentialsRequest $request
     *
     * @return JsonResponse
     *
     * @throws UserNotFoundException
     * @throws FailedToCreateException
     * @throws CredentialsRequestValidationFailedException
     */
    public function updateCredentials(UpdateCredentialsRequest $request): JsonResponse
    {
        $updater = UserService::credentialsUpdater();
        $user = app(AuthenticationClient::class)->currentUser(['email']);

        $hiddenEmail = hidden_string($user->email);

        $updater
            ->parseRequest($request)
            ->createRequest($request->input('current_password'));

        return response()->json([
            'success' => true,
            'message' => 'Запрос на изменение данных успешно отправлен. Вам выслано ' .
                "письмо на $hiddenEmail, чтобы изменения вступили в силу."
        ]);
    }
}
