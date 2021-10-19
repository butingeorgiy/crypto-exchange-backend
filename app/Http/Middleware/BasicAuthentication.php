<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed|void
     */
    public function handle(Request $request, Closure $next)
    {
        $config = config('auth.basic_credentials');

        if ($request->getUser() === $config['user'] && $request->getPassword() === $config['password']) {
            return $next($request);
        }

        abort(401, 'Wrong credentials.', [
            'WWW-Authenticate' => 'Basic'
        ]);
    }
}
