<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAccessCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('verify-code') || $request->is('verify-code/*')) {
            return $next($request);
        }

        if (!$request->session()->has('access_code_verified')) {
            return redirect('/verify-code');
        }

        return $next($request);
    }
}
