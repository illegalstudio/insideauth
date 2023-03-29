<?php

namespace Illegal\InsideAuth\Http\Middleware;

use Closure;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This middleware will ensure that the registration functionality is enabled.
 */
class EnsureRegistrationIsEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!InsideAuth::current()->isRegistrationEnabled()) {
            abort(404);
        }

        return $next($request);
    }
}
