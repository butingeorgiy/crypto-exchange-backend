<?php

namespace App\Services\AuthenticationService;

use App\Models\AuthToken;
use App\Models\User;
use App\Services\AuthenticationService\Drivers\TokenDriverInterface;

class AuthenticationClient
{
    /**
     * Token Driver Instance.
     *
     * @var TokenDriverInterface
     */
    protected TokenDriverInterface $tokenDriver;

    public function __construct(TokenDriverInterface $tokenDriver)
    {
        $this->tokenDriver = $tokenDriver;
    }

    /**
     * Determine if user is authenticated.
     *
     * @param array|string|null $roles
     * @return bool
     */
    public function authenticated(array|string $roles = null): bool
    {
        if (!$tokenInfo = $this->tokenDriver->getTokenInfo()) {
            return false;
        }

        if (gettype($roles) === 'string') {
            $roles = [$roles];
        }

        /** @var AuthToken|null $authToken */
        if (!$authToken = AuthToken::with('user')->find((int)$tokenInfo['token_id'])) {
            return false;
        }

        if (!$this->isUserHasRoles($authToken->user, $roles) && $roles !== null) {
            return false;
        }

        return $this->isTokenValid($authToken->user, $authToken, $tokenInfo['token_hash']);
    }

    /**
     * Determine if user has specified roles.
     *
     * @param User $user
     * @param array $roles
     * @return bool
     */
    protected function isUserHasRoles(User $user, array $roles): bool
    {
        return $user->roles->contains(array_map(function ($role) {
            return ['alias', $role];
        }, $roles));
    }

    /**
     * Determine if token valid.
     *
     * @param User $user
     * @param AuthToken $authToken
     * @param string $tokenHash
     * @return bool
     */
    protected function isTokenValid(User $user, AuthToken $authToken, string $tokenHash): bool
    {
        if (!$authToken->isValid()) {
            return false;
        }

        return $user->isHashValid($authToken, $tokenHash);
    }
}