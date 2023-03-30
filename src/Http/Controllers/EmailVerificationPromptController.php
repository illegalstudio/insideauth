<?php

namespace Illegal\InsideAuth\Http\Controllers;

use Illegal\InsideAuth\Contracts\IsController;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmailVerificationPromptController extends Controller
{
    use IsController;

    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(
                InsideAuth::current()->dashboard ? route(InsideAuth::current()->dashboard) : '/'
            )
            : view('inside_auth::auth.verify-email');
    }
}
