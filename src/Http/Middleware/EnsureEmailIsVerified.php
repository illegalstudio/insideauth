<?php

namespace Illegal\InsideAuth\Http\Middleware;

use Closure;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|null
    {
        /**
         * Only check if email verification is enabled
         */
        if(InsideAuth::current()->isEmailVerificationEnabled()) {
            if (!$request->user() ||
                ( $request->user() instanceof MustVerifyEmail &&
                    !$request->user()->hasVerifiedEmail() )) {

                if ($request->expectsJson()) {
                    abort(403, 'Your email address is not verified.');
                }

                return Redirect::guest(URL::route(InsideAuth::current()->route_verification_notice));
            }
        }

        return $next($request);
    }
}
