<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Authenticator;
use function Pest\Laravel\get;

final class Has
{
    public function __construct(private readonly authenticator $auth)
    {
    }

    public function registerInLogin(): void
    {
        get(route($this->auth->route_login))
            ->assertSee(route($this->auth->route_register));
    }

    public function passwordRequestInLogin(): void
    {
        get(route($this->auth->route_login))
            ->assertSee(route($this->auth->route_password_request));
    }

    public function loginInRegister(): void
    {
        get(route($this->auth->route_register))
            ->assertSee(route($this->auth->route_login));
    }
}