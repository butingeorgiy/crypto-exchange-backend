<?php

namespace App\Services\VerificationService;

use JetBrains\PhpStorm\Pure;

class Client
{
    /**
     * Create verification builder instance.
     *
     * @return VerificationBuilder
     */
    #[Pure] public static function builder(): VerificationBuilder
    {
        return new VerificationBuilder;
    }
}