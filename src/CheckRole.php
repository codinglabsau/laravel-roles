<?php

namespace Codinglabs\Roles;

use Closure;
use Illuminate\Support\Str;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if (Str::contains($roles, '|')) {
            $roles = explode('|', $roles);
        }

        if (!$request->user()->hasRole($roles)) {
            abort(403, 'This action is unauthorized.');
        }

        return $next($request);
    }
}
