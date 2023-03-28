<?php

namespace Illegal\InsideAuth\Http\Middleware;

use Closure;
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
    public function handle(Request $request, Closure $next, string $redirectToRoute = null): Response|RedirectResponse|null
    {
        /**
         * Only check if email verification is enabled
         */
        if(config('inside_auth.functionalities.email_verification')) {
            if (!$request->user('linky') ||
                ( $request->user('linky') instanceof MustVerifyEmail &&
                    !$request->user('linky')->hasVerifiedEmail() )) {

                if ($request->expectsJson()) {
                    abort(403, 'Your email address is not verified.');
                }

                return Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
            }
        }

        return $next($request);
    }
}
