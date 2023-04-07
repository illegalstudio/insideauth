<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Models\User;
use function Pest\Laravel\get;

final class HasNot
{
    public function __construct(private readonly authenticator $auth, private readonly ?User $user = null)
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