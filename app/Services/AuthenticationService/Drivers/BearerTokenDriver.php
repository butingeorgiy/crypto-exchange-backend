<?php

namespace App\Services\AuthenticationService\Drivers;

use Throwable;

class BearerTokenDriver implements TokenDriverInterface
{
    /**
     * @inheritDoc
     */
    public function getTokenInfo(): ?array
    {
        if (!$token = request()->bearerToken()) {
            return null;
        }

        try {
            $token = decrypt($token, false);
        } catch (Throwable) {
            return null;
        }

        $splicedToken = explode('|', $token);

        if (count($splicedToken) !== 2) {
            return null;
        }

        return [
            'token_id' => $splicedToken[0],
            'token_hash' => $splicedToken[1]
        ];
    }
}