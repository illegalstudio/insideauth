<?php

namespace Illegal\InsideAuth\Providers;

use Illegal\InsideAuth\Models\User;
use Illegal\InsideAuth\Passwords\PasswordBrokerManager;
use Illegal\InsideAuth\Registrator;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
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
    }

    /**
     * Register the singletons
     */
    private function registerSingletons() {
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
            return new Registrator(
                $app->make('router'),
                $app->make('config')
            );
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
}
