<?php

namespace Illegal\InsideAuth\Tests;

use Exception;
use Illegal\InsideAuth\InsideAuth;
use Illegal\InsideAuth\Models\User;
use Illegal\InsideAuth\Tests\FeatureHelpers\Exposes;
use Illegal\InsideAuth\Tests\FeatureHelpers\Has;
use Illegal\InsideAuth\Tests\FeatureHelpers\HasNot;
use Illegal\InsideAuth\Tests\FeatureHelpers\Hides;
use Illegal\InsideAuth\Tests\FeatureHelpers\Providers\AuthServiceProvider;
use Illegal\InsideAuth\Tests\FeatureHelpers\Redirects;
use Illegal\InsideAuth\Tests\FeatureHelpers\Routes;

class LoggedInFeatureTestCase extends LoggedOutFeatureTestCase
{

    /**
     * Boot the authentication
     * @throws Exception
     */
    protected function bootAuth(): void
    {
        $user = User::factory()->create();

        $authenticator   = InsideAuth::getAuthenticator(AuthServiceProvider::AUTH_NAME);
        $this->exposes   = new Exposes($authenticator, $user);
        $this->redirects = new Redirects($authenticator, $user);
        $this->routes    = new Routes($authenticator, $user);
        $this->has       = new Has($authenticator, $user);
        $this->hasNot    = new HasNot($authenticator, $user);
        $this->hides     = new Hides($authenticator, $user);
    }
}