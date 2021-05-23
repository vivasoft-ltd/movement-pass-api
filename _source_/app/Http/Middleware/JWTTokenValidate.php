<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class JWTTokenValidate
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
     * @param \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $decodedToken = app('tymon.jwt.provider.jwt')->decode($request->bearerToken());
        $user = $this->auth->guard('admin')->user();

        $isTokenActive = AccessToken::where(['sub' => $user->id, 'jti' => $decodedToken['jti'], 'active' => true])->count();
        if (!$isTokenActive) {
            return response('Token revoked.', 401);
        }

        return $next($request);
    }
}
