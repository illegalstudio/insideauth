<?php

namespace Illegal\InsideAuth\Http\Middleware;

use Closure;
use Exception;
use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{

    /**
     * @var string The route to which the user should be redirected if not authenticated
     */
    private string $route;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string[] ...$guards
     * @return mixed
     * @throws AuthenticationException
     * @throws Exception
     */
    public function handle($request, Closure $next, ...$guards): mixed
    {
        /**
         * We use guards to provide two parameter to this middleware.
         * 1. The route to which the user should be redirected if not authenticated
         * 2. The guards to use for authentication
         *
         * So we use array_shift to get the first parameter (the route) and the rest are the guards
         */
        if(sizeof($guards) < 2) {
            throw new Exception( 'Illegal\InsideAuth\Http\Middleware\Authenticate requires two parameters');
        }
        $this->route = array_shift($guards);

        /**
         * Authenticate only if a valid user is required
         */
        if (config('linky.auth.require_valid_user')) {
            $this->authenticate($request, $guards);
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * Use the login url from the authenticator
     *
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            return route($this->route);
        }

        return null;
    }
}
