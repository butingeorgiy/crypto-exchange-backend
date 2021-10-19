<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ModelExceptions\User\NotFoundException as UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthenticationService\Client as AuthenticationClient;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Get current authenticated user.
     *
     * @return JsonResponse
     *
     * @throws UserNotFoundException
     */
    public function current(): JsonResponse
    {
        $userId = app(AuthenticationClient::class)->currentUserId();

        $user = User::select([
            'first_name',
            'last_name',
            'middle_name',
            'phone_number',
            'email',
            'ref_code',
            'is_verified'
        ])->find($userId) ?: throw new UserNotFoundException;

        return response()->json($user, options: JSON_UNESCAPED_UNICODE);
    }
}
