<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            abort(401, 'Unauthenticated.');
        }

        if ((int) $request->user()->role_id !== 1) {
            abort(403, 'Access denied. Super Admin only.');
        }

        return $next($request);
    }
}
