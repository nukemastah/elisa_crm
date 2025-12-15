<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateSimple
{
    public function handle($request, Closure $next)
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
