<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Tests\FeatureHelpers\Contracts\Helper;

final class Has extends Helper
{

    public function registerInLogin(): void
    {
        $this->testCase()->get(route($this->auth->route_login))
            ->assertSee(route($this->auth->route_register));
    }

    public function passwordRequestInLogin(): void
    {
        $this->testCase()->get(route($this->auth->route_login))
            ->assertSee(route($this->auth->route_password_request));
    }

    public function loginInRegister(): void
    {
        $this->testCase()->get(route($this->auth->route_register))
            ->assertSee(route($this->auth->route_login));
    }
}