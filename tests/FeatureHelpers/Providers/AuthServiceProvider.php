<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers\Providers;

use Exception;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    const AUTH_NAME = 'test';

    /**
     * @throws Exception
     */
    public function boot(): void
    {
        InsideAuth::boot(self::AUTH_NAME);
    }
}