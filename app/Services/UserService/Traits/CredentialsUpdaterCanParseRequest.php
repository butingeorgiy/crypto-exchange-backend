<?php

namespace App\Services\UserService\Traits;

use App\Exceptions\ModelExceptions\User\NotFoundException as UserNotFoundException;
use App\Http\Requests\User\UpdateCredentialsRequest;
use App\Services\AuthenticationService\Client as AuthenticationService;

trait CredentialsUpdaterCanParseRequest
{
    /**
     * Parse HTTP request to user credentials updater.
     *
     * @param UpdateCredentialsRequest $request
     *
     * @return $this
     *
     * @throws UserNotFoundException
     */
    public function parseRequest(UpdateCredentialsRequest $request): static
    {
        $user = app(AuthenticationService::class)->currentUser();

        $updater = $this->setUser($user);

        if ($request->has('email')) {
            $updater->setEmail(
                $request->input('email'),
                $request->input('current_email_code'),
                $request->input('new_email_code')
            );
        }

        if ($request->has('new_password')) {
            $updater->setPassword($request->input('new_password'));
        }

        return $updater;
    }
}