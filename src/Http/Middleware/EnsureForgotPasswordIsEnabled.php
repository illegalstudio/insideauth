<?php

namespace Illegal\InsideAuth\Http\Middleware;

use Closure;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This middleware will ensure that the forgot password functionality is enabled.
 */
class EnsureForgotPasswordIsEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!InsideAuth::current()->forgot_password_enabled) {
            abort(404);
        }

        return $next($request);
    }
}
