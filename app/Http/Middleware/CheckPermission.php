<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (! $request->user() || ! $request->user()->hasPermissionTo($permission)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
