<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers\Providers;

use Illegal\InsideAuth\InsideAuth;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as IlluminateRouteServiceProvider;
use Route;

/**
 * This class will register the routes needed for the tests
 */
class RouteServiceProvider extends IlluminateRouteServiceProvider
{
    public function boot(): void
    {
        /**
         * A dummy homepage route, unprotected
         */
        Route::middleware(InsideAuth::getAuthenticator('test')->middleware_web)
            ->get('/homepage', fn() => 'This is a dummy home')
            ->name('homepage');

        /**
         * A dummy dashboard route, protected
         */
        Route::middleware(InsideAuth::getAuthenticator('test')->middleware_verified)
            ->get('/dashboard', fn() => 'This is a dummy dashboard')
            ->name('dashboard');
    }
}
