<?php

namespace App\Services\AuthenticationService\Traits;

use App\Models\AuthToken;
use App\Services\AuthenticationService\Exceptions\FailedToAttachTokenException;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

trait HasAuthToken
{
    /**
     * Generate and attach authentication token to user.
     * Return string that need use for authentication.
     *
     * @param int $expiration Amount of days when the token is valid
     * @return string Encrypted token hash
     *
     * @throws FailedToAttachTokenException
     */
    public function attachToken(int $expiration = 7): string
    {
        $token = Str::random();

        /** @var AuthToken|false $tokenInstance */
        $tokenInstance = $this->tokens()->save(new AuthToken([
            'token' => $token,
            'expired_at' => now()->addDays($expiration)
        ])) ?: throw new FailedToAttachTokenException;

        return encrypt($tokenInstance->id . '|' . $this->hashToken($token), false);
    }

    /**
     * Return hashed token.
     *
     * @param string $token
     * @return string
     */
    #[Pure]
    protected function hashToken(string $token): string
    {
        return hash('sha256', $token . $this->getPersonalSalt());
    }

    /**
     * Determine is token hash belong to token instance.
     *
     * @param AuthToken $authToken
     * @param string $tokenHash
     * @return bool
     */
    #[Pure]
    public function isHashValid(AuthToken $authToken, string $tokenHash): bool
    {
        return $this->hashToken($authToken->token) === $tokenHash;
    }
}