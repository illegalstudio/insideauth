<?php

use Illegal\InsideAuth\Http\Middleware\Authenticate;
use Illegal\InsideAuth\Http\Middleware\EnsureAuthIsEnabled;
use Illegal\InsideAuth\Http\Middleware\EnsureEmailIsVerified;
use Illegal\InsideAuth\Http\Middleware\InjectIntoApplication;
use Illegal\InsideAuth\Http\Middleware\RedirectIfAuthenticated;
use Illegal\InsideAuth\Registrators\MiddlewareRegistrator;
use Illuminate\Config\Repository;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Collection;
use Illuminate\View\Middleware\ShareErrorsFromSession;

test('middleware registrators handles boot correctly', function ($authName) {

    $config                = Mockery::mock(Repository::class);
    $router                = Mockery::mock(Router::class);
    $middlewareRegistrator = ( new MiddlewareRegistrator($config, $router) )
        ->withAuthName($authName);

    $parameters = $middlewareRegistrator->collectAndMergeParameters();

    expect($parameters)->toBeInstanceOf(Collection::class)
        ->and($parameters->toArray())->toMatchArray([
            'middleware_verified'  => $authName . '-verified',
            'middleware_guest'     => $authName . '-guest',
            'middleware_logged_in' => $authName . '-logged',
            'middleware_web'       => $authName . '-web'
        ]);

    $router->shouldReceive('aliasMiddleware')->withArgs(function ($alias, $class) use ($authName) {

        expect($alias)->toBeIn([
            $authName . '-authenticated',
            $authName . '-ensure-email-is-verified',
            $authName . '-ensure-auth-is-enabled',
            $authName . '-redirect-if-authenticated',
            $authName . '-inject'
        ])->and($class)
            ->when($alias === $authName . "-authenticated", fn(Pest\Expectation $class) => $class->toBe(Authenticate::class))
            ->when($alias === $authName . "-ensure-email-is-verified", fn(Pest\Expectation $class) => $class->toBe(EnsureEmailIsVerified::class))
            ->when($alias === $authName . "-ensure-auth-is-enabled", fn(Pest\Expectation $class) => $class->toBe(EnsureAuthIsEnabled::class))
            ->when($alias === $authName . "-redirect-if-authenticated", fn(Pest\Expectation $class) => $class->toBe(RedirectIfAuthenticated::class))
            ->when($alias === $authName . "-inject", fn(Pest\Expectation $class) => $class->toBe(InjectIntoApplication::class));;

        return true;
    })->times(5);

    $router->shouldReceive('middlewareGroup')->withArgs([
        $authName . '-web',
        [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class
        ]
    ]);

    $router->shouldReceive('middlewareGroup')->withArgs([
        $authName . '-guest',
        [
            $authName . '-inject:' . $authName,
            $authName . '-ensure-auth-is-enabled',
            $authName . '-redirect-if-authenticated'
        ]
    ]);

    $router->shouldReceive('middlewareGroup')->withArgs([
        $authName . '-logged',
        [
            $authName . '-inject:' . $authName,
            $authName . '-ensure-auth-is-enabled',
            $authName . '-authenticated:login,guard'
        ]
    ]);

    $router->shouldReceive('middlewareGroup')->withArgs([
        $authName . '-verified',
        [
            $authName . '-logged',
            $authName . '-ensure-email-is-verified'
        ]
    ]);

    $middlewareRegistrator->boot(collect([
        'route_login'    => 'login',
        'security_guard' => 'guard'
    ]));
})->with(['auth', 'test_auth', 'test-auth']);