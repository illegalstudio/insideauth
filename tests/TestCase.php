<?php

namespace Illegal\InsideAuth\Tests;

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\InsideAuth;
use Illegal\InsideAuth\Providers\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * The name of the authentication
     */
    protected string $authName = 'test';

    /**
     * Boot the authentication
     * @throws \Exception
     */
    protected function bootAuth(): void
    {
        InsideAuth::boot($this->authName);
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
        ];
    }
}
