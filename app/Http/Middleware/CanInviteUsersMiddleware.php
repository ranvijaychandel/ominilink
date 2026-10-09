<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanInviteUsersMiddleware
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

        if (!in_array((int) $request->user()->role_id, [1,2])) {
            abort(403, 'Access denied. You are not authorized.');
        }

        return $next($request);
    }
}
