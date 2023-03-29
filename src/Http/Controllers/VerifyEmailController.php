<?php

namespace Illegal\InsideAuth\Http\Controllers;

use Illegal\InsideAuth\Authenticator;
use Illegal\Linky\Http\Controllers\Controller;
use Illegal\Linky\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        /** @var Authenticator $authenticator */
        $authenticator = $request->attributes->get('authenticator');

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(($authenticator->dashboard() ? route($authenticator->dashboard()) : '/').'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(($authenticator->dashboard() ? route($authenticator->dashboard()) : '/').'?verified=1');
    }
}
