<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class AuthenticateSimple
{
    public function handle($request, Closure $next)
    {
        if (!$request->session()->has('user_id')) {
            return Redirect::route('login');
        }
        return $next($request);
    }
}
