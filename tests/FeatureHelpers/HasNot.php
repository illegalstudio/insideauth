<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Authenticator;
use function Pest\Laravel\get;

final class HasNot
{
    public function __construct(private readonly authenticator $auth)
    {
    }

    public function registerInLogin(): void
    {
        get(route($this->auth->route_login))
            ->assertDontSee(route($this->auth->route_register));
    }

    public function passwordRequestInLogin(): void
    {
        get(route($this->auth->route_login))
            ->assertDontSee(route($this->auth->route_password_request));
    }
}