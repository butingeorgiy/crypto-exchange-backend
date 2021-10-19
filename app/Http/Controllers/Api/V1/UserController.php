<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ModelExceptions\User\FailedToUpdateException as FailedToUpdateUserException;
use App\Exceptions\ModelExceptions\User\NotFoundException as UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateCurrentRequest;
use App\Models\EmailConfirmation;
use App\Models\SmsConfirmation;
use App\Models\User;
use App\Services\AuthenticationService\Client as AuthenticationClient;
use App\Services\EmailConfirmationService\Exceptions\WrongEmailCodeException;
use App\Services\SmsConfirmationService\Client as SmsClient;
use App\Services\EmailConfirmationService\Client as EmailClient;
use App\Services\SmsConfirmationService\Exceptions\WrongSmsCodeException;
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
     * @throws WrongEmailCodeException
     * @throws WrongSmsCodeException
     */
    public function updateCurrent(UpdateCurrentRequest $request): JsonResponse
    {
        $user = app(AuthenticationClient::class)->currentUser();

        $smsService = app(SmsClient::class);
        $emailService = app(EmailClient::class);

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

        if ($request->has('phone_number') && $user->phone_number !== $request->input('phone_number')) {
            # Old phone number confirmation.
            $smsService->verified(
                $user->phone_number,
                $request->input('old_phone_number_confirmation_code')
            ) ?: throw new WrongSmsCodeException('Неверный SMS код для старого номера.');

            # New phone number confirmation.
            $smsService->verified(
                $request->input('phone_number'),
                $request->input('new_phone_number_confirmation_code')
            ) ?: throw new WrongSmsCodeException('Неверный SMS код для нового номера.');

            SmsConfirmation::whereIn('code', [
                $request->input('old_phone_number_confirmation_code'),
                $request->input('new_phone_number_confirmation_code')
            ])->delete();

            $user->phone_number = $request->input('phone_number');
        }

        if ($request->has('email') && $user->email !== $request->input('email')) {
            # Old email confirmation.
            $emailService->verified(
                $user->email,
                $request->input('old_email_confirmation_code')
            ) ?: throw new WrongEmailCodeException('Неверный код для старого E-mail.');

            # New email confirmation.
            $emailService->verified(
                $request->input('email'),
                $request->input('new_email_confirmation_code')
            ) ?: throw new WrongEmailCodeException('Неверный код для нового E-mail.');

            EmailConfirmation::whereIn('code', [
                $request->input('old_email_confirmation_code'),
                $request->input('new_email_confirmation_code')
            ])->delete();

            $user->email = $request->input('email');
        }

        $user->save() ?: throw new FailedToUpdateUserException;

        return response()->json([
            'success' => true,
            'message' => 'Изменения успешно сохранены.'
        ], options: JSON_UNESCAPED_UNICODE);
    }
}
