<?php

namespace App\Services\AuthenticationService\Drivers;

use JetBrains\PhpStorm\ArrayShape;

interface TokenDriverInterface
{
    /**
     * Return token ID and token hash.
     * if it was not possible to get the token, return Null.
     *
     * @return array|null
     */
    #[ArrayShape([
        'token_id' => "int",
        'token_hash' => "string"
    ])]
    public function getTokenInfo(): ?array;
}