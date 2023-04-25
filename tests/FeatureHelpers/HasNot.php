<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Tests\FeatureHelpers\Contracts\Helper;
use function Pest\Laravel\get;

final class HasNot extends Helper
{
    public function registerInLogin(): void
    {
        $this->testCase()->get(route($this->auth->route_login))
            ->assertDontSee(route($this->auth->route_register));
    }

    public function passwordRequestInLogin(): void
    {
        $this->testCase()->get(route($this->auth->route_login))
            ->assertDontSee(route($this->auth->route_password_request));
    }
}