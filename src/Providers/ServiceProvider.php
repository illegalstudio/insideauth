<?php

namespace Illegal\InsideAuth\Providers;

use Illegal\InsideAuth\Builder;
use Illegal\InsideAuth\Passwords\PasswordBrokerManager;
use Illegal\InsideAuth\Registrators\MiddlewareRegistrator;
use Illegal\InsideAuth\Registrators\RouteRegistrator;
use Illegal\InsideAuth\Registrators\SecurityRegistrator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerSingletons();
        $this->bindServices();
    }

    /**
     * Bootstrap the package
     */
    public function boot(): void
    {
        $this->mergeConfigurations();
        $this->loadMigrations();
        $this->loadViews();
        $this->assetsPublishing();
    }

    /**
     * Register the singletons
     */
    private function registerSingletons(): void
    {
        /**
         * The PasswordBroker manager
         */
        $this->app->singleton(PasswordBrokerManager::class, function (Application $app) {
            return new PasswordBrokerManager($app);
        });

        /**
         * The Registrator class, used by the InsideAuth facade
         */
        $this->app->singleton(Builder::class, function (Application $app) {
            return new Builder($app);
        });
    }

    /**
     * Bind the services to the DI
     */
    private function bindServices(): void
    {
        $this->app->bind(MiddlewareRegistrator::class, function(Application $app) {
            return new MiddlewareRegistrator($app->make('config'), $app->make('router'));
        });

        $this->app->bind(RouteRegistrator::class, function(Application $app) {
            return new RouteRegistrator($app->make('config'), $app->make('router'));
        });

        $this->app->bind(SecurityRegistrator::class, function(Application $app) {
            return new SecurityRegistrator($app->make('config'), $app->make('router'), config('inside_auth.db.prefix'));
        });
    }

    /**
     * Merge the configuration
     */
    private function mergeConfigurations(): void
    {
        $this->mergeConfigFrom(__DIR__ . "/../../config/inside_auth.php", "inside_auth");
    }

    /**
     * Load the migrations
     */
    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/../../database/migrations/'
        ]);
    }

    /**
     * Load the views
     */
    private function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'inside_auth');
    }

    private function assetsPublishing(): void
    {
        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../public/build' => public_path('vendor/illegal/inside_auth'),
            ], ['illegal-assets', 'inside-auth-assets', 'laravel-assets']);
        }
    }
}
