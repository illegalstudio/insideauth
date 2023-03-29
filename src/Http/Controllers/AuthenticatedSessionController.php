<?php

namespace Illegal\InsideAuth\Http\Controllers;

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Http\Requests\LoginRequest;
use Illegal\Linky\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('linky::auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        /** @var Authenticator $authenticator */
        $authenticator = $request->attributes->get('authenticator');

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended($authenticator->dashboard() ? route($authenticator->dashboard()) : '/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        /** @var Authenticator $authenticator */
        $authenticator = $request->attributes->get('authenticator');

        Auth::guard($authenticator->security_guard)->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
