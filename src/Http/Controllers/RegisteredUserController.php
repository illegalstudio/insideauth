<?php

namespace Illegal\InsideAuth\Http\Controllers;

use Illegal\InsideAuth\Contracts\IsController;
use Illegal\InsideAuth\Events\Registered;
use Illegal\InsideAuth\InsideAuth;
use Illegal\InsideAuth\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    use IsController;

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view(InsideAuth::current()->template_register);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
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

        Auth::guard(InsideAuth::current()->security_guard)->login($user);

        return redirect(
            InsideAuth::current()->dashboard ? route(InsideAuth::current()->dashboard) : '/'
        );
    }
}
