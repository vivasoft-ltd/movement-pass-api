<?php


namespace App\Http\Middleware;


use App\DataTypes\AdminRole;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        if (!$user = $request->user('admin')) {
            abort(401);
        }

        if ($user->role === 'admin') {
            return $next($request);
        }

        abort(403);
    }
}
