<?php

namespace Illegal\InsideAuth\Http\Middleware;

use Closure;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This middleware will ensure that the email verification functionality is enabled.
 */
class EnsureEmailVerificationIsEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!InsideAuth::current()->email_verification_enabled) {
            abort(404);
        }

        return $next($request);
    }
}
