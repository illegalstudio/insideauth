<?php

namespace Illegal\InsideAuth\Http\Controllers;

use Illegal\InsideAuth\Contracts\IsController;
use Illegal\InsideAuth\Facades\Password;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    use IsController;

    /**
     * Display the password reset link request view.
     *
     * @return View
     */
    public function create(): View
    {
        return view(InsideAuth::current()->template_forgot_password);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
