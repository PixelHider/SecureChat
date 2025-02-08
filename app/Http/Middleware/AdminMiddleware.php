<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth('admin')->check()) {
            return $next($request);
        }
        return redirect('/');  // Redirect to home if not admin
    }
}

