<?php

namespace App\Http\Middleware;

use App\Services\AuthenticationService\Client as AuthenticationClient;
use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles
     *
     * @return mixed|void
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $authService = app(AuthenticationClient::class);

        if ($authService->authenticated(count($roles) > 0 ? $roles : null)) {
            return $next($request);
        } else {
            abort(401, 'Пользователь не авторизован.');
        }
    }
}
