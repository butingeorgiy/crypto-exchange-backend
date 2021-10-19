<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Services\AuthenticationService\Client as AuthenticationClient;
use App\Services\AuthenticationService\Drivers\UnitTestTokenDriver;
use App\Services\AuthenticationService\Exceptions\FailedToAttachTokenException;
use Tests\TestCase;

class AuthenticationServiceTest extends TestCase
{
    /**
     * Check user's valid auth token with right access to one role.
     *
     * @throws FailedToAttachTokenException
     */
    public function test_check_one_right_role(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->roles()->attach(Role::$REGULAR_ROLE_ID);

        $splicedToken = explode('|', decrypt($user->attachToken(), false));

        $authService = new AuthenticationClient(new UnitTestTokenDriver((int) $splicedToken[0], $splicedToken[1]));

        $isUserAuth = $authService->authenticated('regular-user');

        $user->forceDelete();

        $this->assertTrue($isUserAuth);
    }

    /**
     * Check user's valid auth token with wrong access to one role.
     *
     * @throws FailedToAttachTokenException
     */
    public function test_check_one_wrong_role(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->roles()->attach(Role::$REGULAR_ROLE_ID);

        $splicedToken = explode('|', decrypt($user->attachToken(), false));

        $authService = new AuthenticationClient(new UnitTestTokenDriver((int) $splicedToken[0], $splicedToken[1]));

        $isUserAuth = $authService->authenticated('admin');

        $user->forceDelete();

        $this->assertFalse($isUserAuth);
    }

    /**
     * Check user's valid auth token with right and wrong roles.
     *
     * @throws FailedToAttachTokenException
     */
    public function test_check_several_roles(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->roles()->attach(Role::$ADMIN_ROLE_ID);

        $splicedToken = explode('|', decrypt($user->attachToken(), false));

        $authService = new AuthenticationClient(new UnitTestTokenDriver((int) $splicedToken[0], $splicedToken[1]));

        $isUserAuth = $authService->authenticated(['regular-user', 'admin']);

        $user->forceDelete();

        $this->assertTrue($isUserAuth);
    }

    /**
     * Check user's valid auth token with two wrong roles.
     *
     * @throws FailedToAttachTokenException
     */
    public function test_check_several_wrong_roles(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->roles()->attach(Role::$ADMIN_ROLE_ID);

        $splicedToken = explode('|', decrypt($user->attachToken(), false));

        $authService = new AuthenticationClient(new UnitTestTokenDriver((int) $splicedToken[0], $splicedToken[1]));

        $isUserAuth = $authService->authenticated(['regular-user', 'non-existed-role']);

        $user->forceDelete();

        $this->assertFalse($isUserAuth);
    }

    /**
     * Check user's valid auth token with any role.
     *
     * @throws FailedToAttachTokenException
     */
    public function test_check_any_role(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->roles()->attach(Role::$REGULAR_ROLE_ID);

        $splicedToken = explode('|', decrypt($user->attachToken(), false));

        $authService = new AuthenticationClient(new UnitTestTokenDriver((int) $splicedToken[0], $splicedToken[1]));

        $isUserAuth = $authService->authenticated();

        $user->forceDelete();

        $this->assertTrue($isUserAuth);
    }
}
