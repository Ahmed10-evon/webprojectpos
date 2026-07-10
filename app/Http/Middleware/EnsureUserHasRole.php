<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts a route to one or more roles, e.g. ->middleware('role:admin')
 * or ->middleware('role:admin,salesman').
 *
 * This is the single gate that keeps the Salesman account out of
 * "register new product", the entire Purchases module, and purchase data —
 * every route those pages live behind is wrapped with this middleware.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
