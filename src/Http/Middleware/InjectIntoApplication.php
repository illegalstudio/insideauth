<?php

namespace Illegal\InsideAuth\Http\Middleware;

use Closure;
use Illegal\InsideAuth\Builder;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This class will inject the current authenticator into:
 * - The request
 * - The current property of the Registrator / InsideAuth facade
 * @see Builder::current()
 * @see InsideAuth::current()
 */
class InjectIntoApplication
{
    public function handle(Request $request, Closure $next, string $authName): Response
    {
        $authenticator = InsideAuth::getAuthenticator($authName);

        /**
         * Adds an attribute to the request
         */
        $request->attributes->add([
            'authenticator' => $authenticator
        ]);

        /**
         * Register the authenticator as current in the Registrator / InsideAuth facade
         */
        InsideAuth::withCurrent($authenticator);

        return $next($request);
    }

}
