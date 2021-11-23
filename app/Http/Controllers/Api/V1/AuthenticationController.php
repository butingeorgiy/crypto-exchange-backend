<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ModelExceptions\User\NotFoundException as UserNotFoundException;
use App\Exceptions\ModelExceptions\User\WrongPasswordException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\AuthenticateRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Jobs\EmailVerificationJob;
use App\Models\Role;
use App\Models\User;
use App\Services\AuthenticationService\Exceptions\FailedToAttachTokenException;
use App\Services\AuthenticationService\Exceptions\NonEmailVerifiedUserException;
use App\Services\AuthenticationService\Exceptions\UnableToGeneratePersonalSaltException;
use App\Services\SmsConfirmationService\Client as SmsConfirmationClient;
use App\Services\SmsConfirmationService\Exceptions\WrongSmsCodeException;
use Illuminate\Http\JsonResponse;

class AuthenticationController extends Controller
{
    /**
     * Authenticate user by phone and password.
     *
     * @param AuthenticateRequest $request
     *
     * @return JsonResponse
     *
     * @throws UserNotFoundException
     * @throws WrongPasswordException
     * @throws FailedToAttachTokenException
     * @throws NonEmailVerifiedUserException
     * @throws UnableToGeneratePersonalSaltException
     */
    public function authenticate(AuthenticateRequest $request): JsonResponse
    {
        /** @var User|null $user */
        $user = User::byPhone($request->input('phone_number'))
            ->select('id', 'password', 'is_email_verified', 'email')
            ->first() ?: throw new UserNotFoundException;

        $user->is_email_verified ?: throw new NonEmailVerifiedUserException;

        $user->checkPassword($request->input('password'))
            ?: throw new WrongPasswordException;

        # Attach auth token to user.
        $encryptedToken = $user->attachToken();

        return response()->json([
            'success' => true,
            'role' => optional($user->roles->first())->alias,
            'credentials' => [
                'token' => $encryptedToken,
                'available_days' => 7
            ]
        ], options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Register new regular user account.
     *
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'password' => User::hashPassword($request->input('password')),
            'ref_code' => User::generateRefCode()
        ]);

        $user->roles()->attach(Role::$REGULAR_ROLE_ID);

        EmailVerificationJob::dispatch($user)->delay(now()->addSeconds(5));

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно создали аккаунт на Coin Exchange! ' .
                'На ваш E-mail адрес отправлено письмо-подтверждение для завершения регистрации.'
        ], options: JSON_UNESCAPED_UNICODE);
    }
}
