<?php

namespace Illegal\InsideAuth\Http\Controllers;

use Illegal\InsideAuth\Authenticator;
use Illegal\Linky\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        /** @var Authenticator $authenticator */
        $authenticator = $request->attributes->get('authenticator');

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended($authenticator->dashboard() ? route($authenticator->dashboard()) : '/')
                    : view('linky::auth.verify-email');
    }
}
