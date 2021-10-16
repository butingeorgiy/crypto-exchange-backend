<?php

namespace App\Services\AuthenticationService\Drivers;

use JetBrains\PhpStorm\ArrayShape;

class UnitTestTokenDriver implements TokenDriverInterface
{
    protected int $tokenId;

    protected string $tokenHash;

    public function __construct(int $tokenId, string $tokenHash)
    {
        $this->tokenId = $tokenId;
        $this->tokenHash = $tokenHash;
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'token_id' => "int",
        'token_hash' => "string"
    ])]
    public function getTokenInfo(): ?array
    {
        return [
            'token_id' => $this->tokenId,
            'token_hash' => $this->tokenHash
        ];
    }
}