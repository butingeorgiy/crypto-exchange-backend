<?php

namespace App\Services\AuthenticationService\Traits;

use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

trait HasUniqueHashing
{
    /**
     * Return user's personal salt that depends on email and password.
     *
     * @return string
     */
    #[Pure]
    public function getPersonalSalt(): string
    {
        return md5(Str::limit($this->password, 32) . $this->email . self::$hashingSalt);
    }

    /**
     * Check user's password.
     *
     * @param string $password
     * @return bool
     */
    #[Pure]
    public function checkPassword(string $password): bool
    {
        return $this->hashPassword($password) === $this->password;
    }

    /**
     * Hash user's password by unique salt.
     *
     * @param string $password
     * @return string
     */
    #[Pure]
    public static function hashPassword(string $password): string
    {
        return hash('sha256', $password . self::$hashingSalt);
    }
}