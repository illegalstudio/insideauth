<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Authenticator;
use function Pest\Laravel\get;

final class Hides
{
    public function __construct(private readonly Authenticator $auth)
    {
    }

    /**
     * Register should be available
     */
    public function register(): void
    {
        get(route($this->auth->route_register))
            ->assertNotFound() // Should be 404
        ;
    }

    /**
     * Forgot password should be available
     */
    public function forgotPassword(): void
    {
        get(route($this->auth->route_password_request))
            ->assertNotFound() // Should be 404
        ;
    }

    /**
     * Reset password should be available
     */
    public function resetPassword(): void
    {
        get(route($this->auth->route_password_reset, ['token' => 'wrong']))
            ->assertNotFound() // Should be 404
        ;
    }

}