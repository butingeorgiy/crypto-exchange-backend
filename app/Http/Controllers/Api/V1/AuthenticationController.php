<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ModelExceptions\User\WrongPasswordException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\AuthenticateRequest;
use App\Models\User;
use App\Services\AuthenticationService\Exceptions\FailedToAttachTokenException;
use Illuminate\Http\JsonResponse;
use App\Exceptions\ModelExceptions\User\NotFoundException as UserNotFoundException;

class AuthenticationController extends Controller
{
    /**
     * @param AuthenticateRequest $request
     * @return JsonResponse
     * @throws UserNotFoundException
     * @throws WrongPasswordException
     * @throws FailedToAttachTokenException
     */
    public function authenticate(AuthenticateRequest $request): JsonResponse
    {
        /** @var User|null $user */
        $user = User::byPhone($request->input('phone_number'))
            ->select('id')
            ->first() ?: throw new UserNotFoundException;

        !$user->checkPassword($request->input('password'))
            ?: throw new WrongPasswordException;

        # Attach auth token to user.
        $encryptedToken = $user->attachToken();

        return response()->json([
            'success' => true,
            'role' => $user->roles->first()->alias,
            'credentials' => [
                'token' => $encryptedToken,
                'available_days' => 7
            ]
        ], options: JSON_UNESCAPED_UNICODE);
    }
}
