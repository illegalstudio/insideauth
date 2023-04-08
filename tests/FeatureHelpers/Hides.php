<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Tests\FeatureHelpers\Contracts\Helper;
use function Pest\Laravel\get;

final class Hides extends Helper
{
    /**
     * Register should be available
     */
    public function register(): void
    {
        $this->testCase()->get(route($this->auth->route_register))
            ->assertNotFound() // Should be 404
        ;
    }

    /**
     * Forgot password should be available
     */
    public function forgotPassword(): void
    {
        $this->testCase()->get(route($this->auth->route_password_request))
            ->assertNotFound() // Should be 404
        ;
    }

    /**
     * Reset password should be available
     */
    public function resetPassword(): void
    {
        $this->testCase()->get(route($this->auth->route_password_reset, ['token' => 'wrong']))
            ->assertNotFound() // Should be 404
        ;
    }

    /**
     * Profile should not be available
     */
    public function profile(): void
    {
        $this->testCase()->get(route($this->auth->route_profile_edit))
            ->assertNotFound() // Should be 404
        ;
    }

}