<?php

namespace Illegal\InsideAuth\Http\Controllers;

use Illegal\InsideAuth\Authenticator;
use Illegal\Linky\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var Authenticator $authenticator */
        $authenticator = $request->attributes->get('authenticator');

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($authenticator->dashboard() ? route($authenticator->dashboard()) : '/');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
