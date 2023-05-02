<?php

namespace Illegal\InsideAuth\Registrators;

use Illegal\InsideAuth\Contracts\AbstractRegistrator;
use Illegal\InsideAuth\Http\Middleware\Authenticate;
use Illegal\InsideAuth\Http\Middleware\EnsureAuthIsEnabled;
use Illegal\InsideAuth\Http\Middleware\EnsureEmailIsVerified;
use Illegal\InsideAuth\Http\Middleware\InjectIntoApplication;
use Illegal\InsideAuth\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Collection;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * @property string $verified
 * @property string $guest
 * @property string $logged_in
 * @property string $web
 * @property string $authenticated
 * @property string $ensure_verified
 * @property string $ensure_enabled
 * @property string $inject
 * @property string $redirect_authenticated
 */
class MiddlewareRegistrator extends AbstractRegistrator
{
    /**
     * The parameters collection, this will be merged inside the Authenticator, using the prefix
     */
    private Collection $parameters;

    /**
     * @inheritdoc
     */
    protected string $prefix = 'middleware';

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
    public function collectAndMergeParameters(): Collection
    {
        $this->parameters = collect([
            'verified'               => $this->authName.'-verified',
            'guest'                  => $this->authName.'-guest',
            'logged_in'              => $this->authName.'-logged',
            'web'                    => $this->authName.'-web',
            'authenticated'          => $this->authName.'-authenticated',
            'ensure_verified'        => $this->authName.'-ensure-email-is-verified',
            'ensure_enabled'         => $this->authName.'-ensure-auth-is-enabled',
            'inject'                 => $this->authName.'-inject',
            'redirect_authenticated' => $this->authName.'-redirect-if-authenticated',
            'classes'                => [
                Authenticate::class,
                EnsureEmailIsVerified::class,
                EnsureAuthIsEnabled::class,
                RedirectIfAuthenticated::class,
                InjectIntoApplication::class,
            ]
        ]);

        return $this->parameters->except([
            'authenticated',
            'ensure_verified',
            'ensure_enabled',
            'inject',
            'redirect_authenticated'
        ])->mapWithKeys(fn($value, $key) => [$this->prefix.'_'.$key => $value]);
    }

    /**
     * @inheritDoc
     */
    public function boot(Collection $allParameters): void
    {
        /**
         * Aliases for the base middlewares
         * authenticated: Authenticate the user
         * ensure_verified: Ensure that the user is verified
         * redirect_authenticated: Redirect the user if he is already authenticated
         * inject: Inject the authenticator into the request
         */
        $this->router->aliasMiddleware($this->authenticated, Authenticate::class);
        $this->router->aliasMiddleware($this->ensure_verified, EnsureEmailIsVerified::class);
        $this->router->aliasMiddleware($this->ensure_enabled, EnsureAuthIsEnabled::class);
        $this->router->aliasMiddleware($this->redirect_authenticated, RedirectIfAuthenticated::class);
        $this->router->aliasMiddleware($this->inject, InjectIntoApplication::class);

        /**
         * The web middleware, to be used on all web routes
         */
        $this->router->middlewareGroup($this->web, [
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
        $this->router->middlewareGroup($this->guest, [
            $this->inject.':'.$this->authName,
            $this->ensure_enabled,
            $this->redirect_authenticated
        ]);

        /**
         * The logged in middleware, we just check that the user is logged in.
         * It's not necessary that the user is also verified.
         */
        $this->router->middlewareGroup($this->logged_in, [
            $this->inject.':'.$this->authName,
            $this->ensure_enabled,
            $this->authenticated.':'.$allParameters->get('route_login').','.$allParameters->get('security_guard')
        ]);

        /**
         * The main middleware group for the application.
         * Checks that the user is logged in and that is verified.
         */
        $this->router->middlewareGroup($this->verified, [
            $this->logged_in,
            $this->ensure_verified
        ]);
    }
}
