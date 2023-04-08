<?php

namespace Illegal\InsideAuth\Tests;

use Exception;
use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\InsideAuth;
use Illegal\InsideAuth\Models\User;
use Illegal\InsideAuth\Providers\ServiceProvider;
use Illegal\InsideAuth\Tests\FeatureHelpers\Exposes;
use Illegal\InsideAuth\Tests\FeatureHelpers\Has;
use Illegal\InsideAuth\Tests\FeatureHelpers\HasNot;
use Illegal\InsideAuth\Tests\FeatureHelpers\Hides;
use Illegal\InsideAuth\Tests\FeatureHelpers\Providers\AuthServiceProvider;
use Illegal\InsideAuth\Tests\FeatureHelpers\Providers\RouteServiceProvider;
use Illegal\InsideAuth\Tests\FeatureHelpers\Redirects;
use Illegal\InsideAuth\Tests\FeatureHelpers\Routes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class LoggedOutFeatureTestCase extends TestCase
{
    use RefreshDatabase;

    protected Exposes   $exposes;
    protected Redirects $redirects;
    protected Routes    $routes;
    protected Has       $has;
    protected HasNot    $hasNot;
    protected Hides     $hides;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    /**
     * Boot the authentication
     * @throws Exception
     */
    protected function bootAuth(): void
    {
        $authenticator   = InsideAuth::getAuthenticator(AuthServiceProvider::AUTH_NAME);
        $this->exposes   = new Exposes($authenticator);
        $this->redirects = new Redirects($authenticator);
        $this->routes    = new Routes($authenticator);
        $this->has       = new Has($authenticator);
        $this->hasNot    = new HasNot($authenticator);
        $this->hides     = new Hides($authenticator);
    }

    /**
     * Returns the Authenticator
     */
    protected function auth(): Authenticator
    {
        return InsideAuth::getAuthenticator(AuthServiceProvider::AUTH_NAME);
    }

    /**
     * Returns the package providers needed for the tests
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            AuthServiceProvider::class,
            RouteServiceProvider::class
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getEnvironmentSetUp($app)
    {
        $config = $app->get('config');
        $config->set('logging.default', 'errorlog');
        $config->set('database.default', 'testbench');
        $config->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
