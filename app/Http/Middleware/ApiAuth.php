<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class ApiAuth
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @internal param null|string $guard
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('Authorization')) {
            $keys = explode(' ',$request->header('Authorization'));
            if (count($keys) === 2 && $user = User::whereSessionToken($keys[1])->first()) 
            {
                // if (!$user->is_blocked) {
                    $this->auth->setUser($user);
                    return $next($request);
                // }
                error(config('messages.general.account_suspended'));
            }
        }
        return response('Unauthorized.', 401);
    }
}
