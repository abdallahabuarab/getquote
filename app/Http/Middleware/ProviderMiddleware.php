<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class ProviderMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isProvider()) {
            return $next($request);
        }

        return redirect('/unauthorized')->with('error', 'Access denied.');
    }
}
