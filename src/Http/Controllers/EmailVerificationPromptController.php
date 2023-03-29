<?php

namespace Illegal\InsideAuth\Http\Controllers;

use Illegal\InsideAuth\InsideAuth;
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
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(
                InsideAuth::current()->dashboard() ? route(InsideAuth::current()->dashboard()) : '/'
            )
            : view('linky::auth.verify-email');
    }
}
