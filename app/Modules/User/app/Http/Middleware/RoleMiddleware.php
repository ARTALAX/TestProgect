<?php

namespace Modules\User\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request         $request
     * @param string|string[] ...$roles
     *
     * @return mixed|Response
     */
    public function handle($request, \Closure $next, ...$roles): mixed
    {
        $user = $request->user();
        if (!$user || !in_array(needle: $user->role, haystack: $roles, strict: true)) {
            return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
