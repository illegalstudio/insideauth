<?php

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Http\Middleware\Authenticate;
use Illegal\InsideAuth\Http\Middleware\EnsureAuthIsEnabled;
use Illegal\InsideAuth\Http\Middleware\EnsureEmailIsVerified;
use Illegal\InsideAuth\Http\Middleware\InjectIntoApplication;
use Illegal\InsideAuth\Http\Middleware\RedirectIfAuthenticated;
use Illegal\InsideAuth\Registrators\MiddlewareRegistrator;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

beforeEach(function () {
    /** @noinspection PhpDynamicFieldDeclarationInspection */
    $authenticator = Mockery::mock(Authenticator::class);
    /** @noinspection PhpParamsInspection */
    $authenticator
        ->shouldReceive('name')
        ->andSet('parameters', collect([
            'route_login'    => 'login',
            'security_guard' => 'guard'
        ]))
        ->andReturn('auth');

    $authenticator->shouldReceive('merge')->withArgs(function ($args) {

        expect($args)->toBeInstanceOf(Collection::class)
            ->and($args->count())->toBe(4)
            ->and($args->get('middleware_verified'))->toBe('auth-verified')
            ->and($args->get('middleware_guest'))->toBe('auth-guest')
            ->and($args->get('middleware_logged_in'))->toBe('auth-logged')
            ->and($args->get('middleware_web'))->toBe('auth-web');

        return true;
    });
    /** @noinspection PhpDynamicFieldDeclarationInspection */
    $this->middlewareRegistrator = (new MiddlewareRegistrator())
        ->withAuthenticator($authenticator)
        ->collectAndMergeParameters();
});

test('middleware registrators handles boot correctly', function () {
    Route::shouldReceive('aliasMiddleware')->withArgs(function ($alias, $class) {

        expect($alias)->toBeIn([
            'auth-authenticated',
            'auth-ensure-email-is-verified',
            'auth-ensure-auth-is-enabled',
            'auth-redirect-if-authenticated',
            'auth-inject'
        ])->and($class)
            ->when($alias === "auth-authenticated", fn(Pest\Expectation $class) => $class->toBe(Authenticate::class))
            ->when($alias === "auth-ensure-email-is-verified", fn(Pest\Expectation $class) => $class->toBe(EnsureEmailIsVerified::class))
            ->when($alias === "auth-ensure-auth-is-enabled", fn(Pest\Expectation $class) => $class->toBe(EnsureAuthIsEnabled::class))
            ->when($alias === "auth-redirect-if-authenticated", fn(Pest\Expectation $class) => $class->toBe(RedirectIfAuthenticated::class))
            ->when($alias === "auth-inject", fn(Pest\Expectation $class) => $class->toBe(InjectIntoApplication::class));;

        return true;
    })->times(5);

    Route::shouldReceive('middlewareGroup')->withArgs(function ($alias, array $data) {
        expect($alias)->toBeIn([
            'auth-web',
            'auth-guest',
            'auth-logged',
            'auth-verified'
        ])->and($data)
            ->when($alias === "auth-web", fn($data) => $data->toMatchArray([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class
            ]))
            ->when($alias === "auth-guest", fn(Pest\Expectation $data) => $data->toMatchArray([
                'auth-inject:auth',
                'auth-ensure-auth-is-enabled',
                'auth-redirect-if-authenticated'
            ]))
            ->when($alias === "auth-logged", fn(Pest\Expectation $data) => $data->toMatchArray([
                'auth-inject:auth',
                'auth-ensure-auth-is-enabled',
                'auth-authenticated:login,guard'
            ]))
            ->when($alias === "auth-verified", fn(Pest\Expectation $data) => $data->toMatchArray([
                'auth-logged',
                'auth-ensure-email-is-verified'
            ]));

        return true;
    })->times(4);

    $this->middlewareRegistrator->boot();
});