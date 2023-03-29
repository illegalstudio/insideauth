<?php

namespace Illegal\InsideAuth\Http\Middleware;

use Closure;
use Illegal\InsideAuth\Authenticator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $guard
     * @param $redirectToRoute
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        /** @var Authenticator $authenticator */
        $authenticator = $request->attributes->get('authenticator');

        if (Auth::guard($authenticator->security_guard)->check()) {
            return redirect($authenticator->dashboard() ? route($authenticator->dashboard()) : '/');
        }

        return $next($request);
    }
}
