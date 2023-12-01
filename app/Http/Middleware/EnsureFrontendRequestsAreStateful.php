<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use Illuminate\Support\Facades\Auth;

class EnsureFrontendRequestsAreStateful extends Middleware
{
    // ...
    public function handle($request, Closure $next)
    {
        if (Auth::guard('sanctum')->check()) {
            // User authenticated using 'auth:sanctum'
            return $next($request);
        } else {
            // User not authenticated using 'auth:sanctum'
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }
}