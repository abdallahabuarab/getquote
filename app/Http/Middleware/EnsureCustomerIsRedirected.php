<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerIsRedirected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $protectedRoutes = [
            'confirm.service',
            'finalize.interaction',
            'order.complete'
        ];

        $currentRouteName = $request->route()->getName();

        if (in_array($currentRouteName, $protectedRoutes) && !$request->session()->has('started_process')) {
            return redirect()->route('index');
        }

        return $next($request);
    }
}
