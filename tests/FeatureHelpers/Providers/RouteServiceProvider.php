<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as IlluminateRouteServiceProvider;
use Route;

/**
 * This class will register the routes needed for the tests
 */
class RouteServiceProvider extends IlluminateRouteServiceProvider
{
    public function boot(): void
    {
        Route::get('/dashboard', fn() => 'This is a dummy dashboard')->name('dashboard');
    }
}