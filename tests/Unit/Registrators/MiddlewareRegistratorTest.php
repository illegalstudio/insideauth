<?php

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Http\Middleware\Authenticate;
use Illegal\InsideAuth\Http\Middleware\EnsureAuthIsEnabled;
use Illegal\InsideAuth\Http\Middleware\EnsureEmailIsVerified;
use Illegal\InsideAuth\Http\Middleware\InjectIntoApplication;
use Illegal\InsideAuth\Http\Middleware\RedirectIfAuthenticated;
use Illegal\InsideAuth\Registrators\MiddlewareRegistrator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    /** @noinspection PhpDynamicFieldDeclarationInspection */
    $authenticator = Mockery::mock(Authenticator::class);
    /** @noinspection PhpParamsInspection */
    $authenticator
        ->shouldReceive('name')
        ->andSet('parameters', (new Collection))
        ->andReturn('auth');

    $authenticator->shouldReceive('merge')->withArgs(function($args) {

        expect($args)->toBeInstanceOf(Collection::class)
            ->and($args->count())->toBe(4)
            ->and($args->get('middleware_verified'))->toBe('auth-verified')
            ->and($args->get('middleware_guest'))->toBe('auth-guest')
            ->and($args->get('middleware_logged_in'))->toBe('auth-logged')
            ->and($args->get('middleware_web'))->toBe('auth-web');

        return true;
    });
    /** @noinspection PhpDynamicFieldDeclarationInspection */
    $this->middlewareRegistrator = new MiddlewareRegistrator($authenticator);
});

test('test', function () {
    Route::shouldReceive('aliasMiddleware')->withArgs(function($alias, $class) {

        expect($alias)->toBeIn([
            'auth-authenticated',
            'auth-ensure-email-is-verified',
            'auth-ensure-auth-is-enabled',
            'auth-redirect-if-authenticated',
            'auth-inject'
        ])->and($class)
            ->when($alias === "auth-authenticated", fn($class) => $class->toBe(Authenticate::class))
            ->when($alias === "auth-ensure-email-is-verified", fn($class) => $class->toBe(EnsureEmailIsVerified::class))
            ->when($alias === "auth-ensure-auth-is-enabled", fn($class) => $class->toBe(EnsureAuthIsEnabled::class))
            ->when($alias === "auth-redirect-if-authenticated", fn($class) => $class->toBe(RedirectIfAuthenticated::class))
            ->when($alias === "auth-inject", fn($class) => $class->toBe(InjectIntoApplication::class));
        ;

        return true;
    });

    Route::shouldReceive('middlewareGroup');

    $this->middlewareRegistrator->boot();
});