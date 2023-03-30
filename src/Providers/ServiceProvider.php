<?php

namespace Illegal\InsideAuth\Providers;

use Illegal\InsideAuth\Passwords\PasswordBrokerManager;
use Illegal\InsideAuth\Registrators\Registrator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->registerSingletons();
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
    private function registerSingletons()
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
        $this->app->singleton(Registrator::class, function (Application $app) {
            return new Registrator();
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
