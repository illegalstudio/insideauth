<?php

namespace Illegal\InsideAuth\Http\Controllers;

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Events\Registered;
use Illegal\InsideAuth\Models\User;
use Illegal\Linky\Http\Controllers\Controller;
use Illegal\Linky\LinkyAuth;
use Illegal\Linky\RouteServiceProvider;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('linky::auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var Authenticator $authenticator */
        $authenticator = $request->attributes->get('authenticator');

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        /**
         * @todo Store the authenticator in the user
         */
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::guard($authenticator->security_guard)->login($user);

        return redirect($authenticator->dashboard() ? route($authenticator->dashboard()) : '/');
    }
}
