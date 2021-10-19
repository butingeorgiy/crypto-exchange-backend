<?php

namespace App\Services\AuthenticationService;

use App\Models\AuthToken;
use App\Models\User;
use App\Services\AuthenticationService\Drivers\TokenDriverInterface;
use App\Services\AuthenticationService\Exceptions\UnableToGeneratePersonalSaltException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client
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
        if (!$authToken = AuthToken::with('user')->find((int) $tokenInfo['token_id'])) {
            return false;
        }

        if (!is_null($roles) && !$this->isUserHasRoles($authToken->user, $roles)) {
            return false;
        }

        try {
            $result = $this->isTokenValid($authToken->user, $authToken, $tokenInfo['token_hash']);
        } catch (UnableToGeneratePersonalSaltException) {
            return false;
        }

        return $result;
    }

    /**
     * Get current authenticated user ID.
     *
     * @return int|null
     */
    public function currentUserId(): ?int
    {
        if (!$tokenInfo = $this->tokenDriver->getTokenInfo()) {
            return null;
        }

        /** @var AuthToken|null $authToken */
        if (!$authToken = AuthToken::with('user')->find((int) $tokenInfo['token_id'])) {
            return null;
        }

        /** @var User $user */
        $user = $authToken->user()->select('id')->first();

        return $user->id;
    }

    /**
     * Get current authenticated user.
     *
     * @param array|null $fields
     *
     * @return User|null
     */
    public function currentUser(array $fields = null): ?User
    {
        if (!$tokenInfo = $this->tokenDriver->getTokenInfo()) {
            return null;
        }

        /** @var AuthToken|null $authToken */
        if (!$authToken = AuthToken::with('user')->find((int) $tokenInfo['token_id'])) {
            return null;
        }

        if (is_null($fields)) {
            $user = $authToken->user;
        } else {
            /** @var User|null $user */
            $user = $authToken->user()->select($fields)->first();
        }

        return $user;
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
        return $user->roles->contains('alias', ...$roles);
    }

    /**
     * Determine if token valid.
     *
     * @param User $user
     * @param AuthToken $authToken
     * @param string $tokenHash
     *
     * @return bool
     *
     * @throws Exceptions\UnableToGeneratePersonalSaltException
     */
    protected function isTokenValid(User $user, AuthToken $authToken, string $tokenHash): bool
    {
        if (!$authToken->isValid()) {
            return false;
        }

        return $user->isHashValid($authToken, $tokenHash);
    }
}