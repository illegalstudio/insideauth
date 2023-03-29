<?php

namespace Illegal\InsideAuth\Http\Middleware;

use Closure;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This class will inject the current authenticator into the request.
 */
class InjectIntoRequest
{
    public function handle(Request $request, Closure $next, string $authName): Response
    {
        $request->attributes->add([
            'authenticator' => InsideAuth::getAuthenticator($authName),
        ]);

        return $next($request);
    }

}
