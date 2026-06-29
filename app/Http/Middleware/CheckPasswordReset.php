<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordReset
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->mustChangePassword()) {
            if (! $request->routeIs('password.required') && ! $request->routeIs('logout')) {
                return redirect()->route('password.required');
            }
        }

        return $next($request);
    }
}
