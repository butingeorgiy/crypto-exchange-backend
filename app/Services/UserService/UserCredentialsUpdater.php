<?php

namespace App\Services\UserService;

use App\Exceptions\ModelExceptions\User\NotFoundException as UserNotFoundException;
use App\Exceptions\ModelExceptions\UserCredentialsUpdateRequest\FailedToCreateException as FailedToCreateRequestException;
use App\Exceptions\ModelExceptions\UserCredentialsUpdateRequest\NotFoundException as RequestNotFoundException;
use App\Jobs\UserCredentialsUpdatingJob;
use App\Models\User;
use App\Models\UserCredentialsUpdateRequest;
use App\Services\EmailConfirmationService\Client as EmailConfirmationService;
use App\Services\UserService\Exceptions\CredentialsRequestValidationFailedException;
use App\Services\UserService\Exceptions\EmptyUpdatesException;
use App\Services\UserService\Exceptions\FailedToUpdateCredentialsException;
use App\Services\UserService\Exceptions\UserNotSetException;
use App\Services\UserService\Traits\CredentialsUpdaterCanParseRequest as CanParseHttpRequest;
use Exception;
use Throwable;

class UserCredentialsUpdater
{
    use CanParseHttpRequest;

    /**
     * User model instance.
     *
     * @var User
     */
    protected User $user;

    /**
     * User's new password.
     *
     * @var string
     */
    protected string $password;

    /**
     * User's new E-mail address.
     *
     * @var string
     */
    protected string $email;

    /**
     * Code from user's current E-mail address.
     *
     * @var string
     */
    protected string $currentEmailCode;

    /**
     * Code from user's new E-mail address.
     *
     * @var string
     */
    protected string $newEmailCode;

    /**
     * Set user model instance.
     *
     * @param User|int $user
     *
     * @return $this
     *
     * @throws UserNotFoundException
     */
    public function setUser(User|int $user): static
    {
        if (is_integer($user)) {
            $user = User::find($user) ?: throw new UserNotFoundException;
        }

        $this->user = $user;

        return $this;
    }

    /**
     * Set user's new password for updating.
     *
     * @param string $newPassword
     *
     * @return $this
     */
    public function setPassword(string $newPassword): static
    {
        $this->password = $newPassword;

        return $this;
    }

    /**
     * Set user's new E-mail address for updating.
     *
     * @param string $newEmail
     * @param string $currentEmailCode
     * @param string $newEmailCode
     *
     * @return $this
     */
    public function setEmail(string $newEmail, string $currentEmailCode, string $newEmailCode): static
    {
        $this->email = $newEmail;
        $this->currentEmailCode = $currentEmailCode;
        $this->newEmailCode = $newEmailCode;

        return $this;
    }

    /**
     * Create user credentials update request and notify user about it.
     *
     * @param string $currentPassword
     *
     * @throws CredentialsRequestValidationFailedException
     * @throws FailedToCreateRequestException
     */
    public function createRequest(string $currentPassword): void
    {
        $this->validateBeforeRequestCreating($currentPassword);

        $preparedRequest = UserCredentialsUpdateRequest::prepareUnique();
        $preparedRequest->user()->associate($this->user);

        if (isset($this->email)) {
            $preparedRequest->email = $this->email;
        }

        if (isset($this->password)) {
            $preparedRequest->hashed_password = User::hashPassword($this->password);
        }

        $preparedRequest->save() ?: throw new FailedToCreateRequestException;

        UserCredentialsUpdatingJob::dispatch($preparedRequest)->delay(now()->addSeconds(5));
    }

    /**
     * Validate updater properties before updates request creating.
     *
     * @param string $currentPassword
     *
     * @throws CredentialsRequestValidationFailedException
     */
    protected function validateBeforeRequestCreating(string $currentPassword): void
    {
        try {
            isset($this->user) ?: throw new UserNotSetException;

            if (!$this->user->checkPassword($currentPassword)) {
                throw new Exception('Текущий пароль неверный.');
            }

            # Check updater for empty updates.
            if (!isset($this->email) && !isset($this->password)) {
                throw new EmptyUpdatesException;
            }

            # Check E-mail confirmation codes.

            if (isset($this->email) && (!isset($this->currentEmailCode) || !isset($this->newEmailCode))) {
                throw new Exception('Codes from Current and New E-mail must be set for E-mail update.');
            }

            if (isset($this->email)) {
                $emailConfirmationService = app(EmailConfirmationService::class);

                if (!$emailConfirmationService->verified($this->user->email, $this->currentEmailCode)) {
                    throw new Exception('Код для текущего E-mail адреса неверный.');
                }

                if (!$emailConfirmationService->verified($this->email, $this->newEmailCode)) {
                    throw new Exception('Код для нового E-mail адреса неверный.');
                }
            }
        } catch (Throwable $e) {
            throw new CredentialsRequestValidationFailedException($e->getMessage());
        }
    }

    /**
     * Apply user's credentials updates from updates request instance.
     *
     * @param string $requestId
     * @param string $salt
     *
     * @throws FailedToUpdateCredentialsException
     * @throws RequestNotFoundException
     */
    public function apply(string $requestId, string $salt): void
    {
        $request = UserCredentialsUpdateRequest::where([
            ['id', $requestId],
            ['salt', $salt]
        ])->first() ?: throw new RequestNotFoundException;

        $user = $request->user;

        if ($request->email) {
            $user->email = $request->email;
        }

        if ($request->hashed_password) {
            $user->password = $request->hashed_password;
        }

        $user->save() ?: throw new FailedToUpdateCredentialsException;
        $request->delete();
    }
}