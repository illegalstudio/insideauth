<?php

namespace Illegal\InsideAuth;

use Illegal\InsideAuth\Http\Middleware\Authenticate;
use Illegal\InsideAuth\Http\Middleware\EncryptCookies;
use Illegal\InsideAuth\Http\Middleware\EnsureEmailIsVerified;
use Illegal\InsideAuth\Http\Middleware\RedirectIfAuthenticated;
use Illegal\InsideAuth\Http\Middleware\VerifyCsrfToken;
use Illegal\InsideAuth\Models\User;
use Illuminate\Config\Repository;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;

final class Registrator
{
    public function __construct(private readonly Router $router, private Repository $config)
    {
    }

    /**
     * Register a new Auth set using the provided name
     */
    public function register(string $name): void
    {
        $this->config($name);
        $this->middlewares($name);
    }

    public function boot(string $name): void
    {
    }

    /**
     * With this middleware, guest users are allowed. Logged in users will be redirected to the home page.
     */
    public function getGuestMiddlewareName(string $name): string
    {
        return $name . '-guest';
    }

    /**
     * The common web middleware, it includes the csrf token verification and the encryption of the cookies.
     */
    public function getWebMiddlewareName(string $name): string
    {
        return $name . '-web';
    }

    /**
     * The first level of authentication, it checks if the user is logged in.
     */
    public function getIsLoggedInMiddlewareName(string $name): string
    {
        return $name . '-logged';
    }

    /**
     * The second level of authentication, it checks if the user is verified.
     */
    public function getMiddlewareName(string $name): string
    {
        return $name;
    }

    /**
     * Alias to Authenticate middleware class. It is used internally and augmented with the guard name.
     */
    private function getAuthenticatedMiddlewareName(string $name): string
    {
        return $name . '-authenticated';
    }

    /**
     * Alias to EnsureEmailIsVerified middleware class. It is used internally and augmented with the redirect route.
     */
    private function getEnsureEmailIsVerifiedMiddlewareName(string $name): string
    {
        return $name . '-ensure-email-is-verified';
    }

    /**
     * The name of the guard
     */
    public function getGuardName($name): string
    {
        return $name;
    }

    /**
     * The name of the user provider
     */
    public function getProviderName($name): string
    {
        return $name;
    }

    /**
     * The name of the password broker
     */
    public function getPasswordBrokerName($name): string
    {
        return $name;
    }

    /**
     * Register the middlewares
     */
    private function middlewares($name): void
    {
        $this->router->aliasMiddleware($this->getGuestMiddlewareName($name), RedirectIfAuthenticated::class);
        $this->router->aliasMiddleware($this->getAuthenticatedMiddlewareName($name), Authenticate::class);
        $this->router->aliasMiddleware($this->getEnsureEmailIsVerifiedMiddlewareName($name), EnsureEmailIsVerified::class);

        /**
         * The main authenticate middleware, we pass the name of the guard
         */
        $this->router->middlewareGroup($this->getIsLoggedInMiddlewareName($name), [
            $this->getAuthenticatedMiddlewareName($name) . ':' . $this->getGuardName($name)
        ]);

        $this->router->middlewareGroup($this->getWebMiddlewareName($name), [
            EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        /**
         * The main middleware group for the application. Checks that the user is logged in and that the email
         * is verified.
         */
        $this->router->middlewareGroup($this->getMiddlewareName($name), [
            $this->getEnsureEmailIsVerifiedMiddlewareName($name) . ':linky.auth.verification.notice',
            $this->getIsLoggedInMiddlewareName($name)
        ]);

    }

    /**
     * Configure the auth system
     */
    private function config($name): void
    {
        Config::set('auth.guards.' . $this->getGuardName($name), [
            'driver'   => 'session',
            'provider' => $this->getProviderName($name),
        ]);

        // Will use the EloquentUserProvider driver with the Admin model
        Config::set('auth.providers.' . $this->getProviderName($name), [
            'driver' => 'eloquent',
            'model'  => User::class
        ]);

        Config::set('auth.passwords.' . $this->getPasswordBrokerName($name), [
            'provider' => $this->getProviderName($name),
            'table'    => config('linky.db.prefix') . 'password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ]);
    }
}
