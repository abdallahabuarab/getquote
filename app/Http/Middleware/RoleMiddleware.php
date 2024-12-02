<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            if ($user->provider) {
                session(['role' => 'provider']);
            } else {
                session(['role' => 'user']);
            }
        }

        return $next($request);
    }
}
