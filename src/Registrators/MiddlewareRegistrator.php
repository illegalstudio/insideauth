<?php

namespace Illegal\InsideAuth\Registrators;

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Contracts\RegistratorInterface;
use Illegal\InsideAuth\Http\Middleware\Authenticate;
use Illegal\InsideAuth\Http\Middleware\EnsureEmailIsVerified;
use Illegal\InsideAuth\Http\Middleware\InjectIntoRequest;
use Illegal\InsideAuth\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * @property string $verified
 * @property string $guest
 * @property string $logged_in
 * @property string $web
 * @property string $authenticated
 * @property string $ensure_verified
 * @property string $inject
 * @property string $redirect_authenticated
 */
class MiddlewareRegistrator implements RegistratorInterface
{
    /**
     * The parameters collection, this will be merged inside the Authenticator, using the prefix
     */
    private Collection $parameters;

    /**
     * @inheritDoc
     */
    public function __construct(private readonly Authenticator $authenticator, string $prefix = 'middleware')
    {
        $this->parameters = collect([
            'verified'               => $this->authenticator->name(),
            'guest'                  => $this->authenticator->name() . '-guest',
            'logged_in'              => $this->authenticator->name() . '-logged',
            'web'                    => $this->authenticator->name() . '-web',
            'authenticated'          => $this->authenticator->name() . '-authenticated',
            'ensure_verified'        => $this->authenticator->name() . '-ensure-email-is-verified',
            'inject'                 => $this->authenticator->name() . '-inject',
            'redirect_authenticated' => $this->authenticator->name() . '-redirect-if-authenticated',
        ]);

        $this->authenticator->merge($this->parameters->except([
            'authenticated',
            'ensure_verified',
            'inject',
            'redirect_authenticated'
        ])->mapWithKeys(fn($value, $key) => [$prefix . '_' . $key => $value]));
    }

    /**
     * @inheritDoc
     */
    public function __get($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        /**
         * Aliases for the base middlewares
         * authenticated: Authenticate the user
         * ensure_verified: Ensure that the user is verified
         * redirect_authenticated: Redirect the user if he is already authenticated
         * inject: Inject the authenticator into the request
         */
        Route::aliasMiddleware($this->authenticated, Authenticate::class);
        Route::aliasMiddleware($this->ensure_verified, EnsureEmailIsVerified::class);
        Route::aliasMiddleware($this->redirect_authenticated, RedirectIfAuthenticated::class);
        Route::aliasMiddleware($this->inject, InjectIntoRequest::class);

        /**
         * The web middleware, to be used on all web routes
         */
        Route::middlewareGroup($this->web, [
            EncryptCookies::class,                  // From Laravel
            AddQueuedCookiesToResponse::class,      // From Laravel
            StartSession::class,                    // From Laravel
            ShareErrorsFromSession::class,          // From Laravel
            VerifyCsrfToken::class,                 // From Laravel
            SubstituteBindings::class,              // From Laravel
        ]);

        /**
         * The guest middleware. Authenticated users will be redirected.
         */
        Route::middlewareGroup($this->guest, [
            $this->inject . ':' . $this->authenticator->name(),
            $this->redirect_authenticated
        ]);

        /**
         * The logged in middleware, we just check that the user is logged in.
         * It's not necessary that the user is also verified.
         */
        Route::middlewareGroup($this->logged_in, [
            $this->inject . ':' . $this->authenticator->name(),
            $this->authenticated . ':' . $this->authenticator->route_login . ',' . $this->authenticator->security_guard
        ]);

        /**
         * The main middleware group for the application.
         * Checks that the user is logged in and that is verified.
         */
        Route::middlewareGroup($this->verified, [
            $this->logged_in,
            $this->ensure_verified
        ]);
    }
}