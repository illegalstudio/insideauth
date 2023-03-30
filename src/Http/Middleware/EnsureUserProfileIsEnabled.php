<?php

namespace Illegal\InsideAuth\Http\Middleware;

use Closure;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This middleware will ensure that the profile functionality is enabled.
 */
class EnsureUserProfileIsEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!InsideAuth::current()->user_profile_enabled) {
            abort(404);
        }

        return $next($request);
    }
}
