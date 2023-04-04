<?php

namespace Illegal\InsideAuth\Tests;

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\InsideAuth;
use Illegal\InsideAuth\Providers\ServiceProvider;
use Illegal\InsideAuth\Tests\FeatureHelpers\Exposes;
use Illegal\InsideAuth\Tests\FeatureHelpers\Providers\RouteServiceProvider;
use Illegal\InsideAuth\Tests\FeatureHelpers\Redirects;
use Illegal\InsideAuth\Tests\FeatureHelpers\Routes;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class FeatureTestCase extends BaseTestCase
{
    /**
     * The name of the authentication
     */
    protected string $authName = 'test';

    protected Exposes   $exposes;
    protected Redirects $redirects;
    protected Routes    $routes;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    /**
     * Boot the authentication
     * @throws \Exception
     */
    protected function bootAuth(): void
    {
        $authenticator   = InsideAuth::boot($this->authName);
        $this->exposes   = new Exposes($authenticator);
        $this->redirects = new Redirects($authenticator);
        $this->routes    = new Routes($authenticator);
    }

    /**
     * Returns the Authenticator
     */
    protected function auth(): Authenticator
    {
        return InsideAuth::getAuthenticator($this->authName);
    }

    /**
     * Returns the package providers needed for the tests
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            RouteServiceProvider::class
        ];
    }
}
