<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function redirectTo(Request $request, Closure $next, $guard = null)
    {
        if (!$request->expectsJson()) {
            if ($request->is('customer*')) {
                return route('customer.login');
            }
            return route('login');
        }
    }
}
