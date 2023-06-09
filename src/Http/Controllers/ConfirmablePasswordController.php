<?php

namespace Illegal\InsideAuth\Http\Controllers;

use Illegal\InsideAuth\Contracts\IsController;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ConfirmablePasswordController extends Controller
{
    use IsController;

    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view(InsideAuth::current()->template_confirm_password);
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::guard(InsideAuth::current()->security_guard)->validate([
            'email'    => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(
            InsideAuth::current()->dashboard ? route(InsideAuth::current()->dashboard) : '/'
        );
    }
}
