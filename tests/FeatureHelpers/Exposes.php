<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Models\User;
use function Pest\Laravel\get;

final class Exposes
{
    public function __construct(private readonly Authenticator $auth, private readonly ?User $user = null)
    {
    }

    /**
     * The dummy dashboard - defined in the test suite - should be available
     */
    public function dummyDashboard(): void
    {
        get(route('dashboard'))
            ->assertOk()
            ->assertSee('This is a dummy dashboard');
    }

    /**
     * Login should be available
     */
    public function login(): void
    {
        get(route($this->auth->route_login))
            ->assertOk() // Should be 200
            ->assertSee('name="email"', false) // Email field should be present
            ->assertSee('name="password"', false) // Password field should be present
            ->assertSee('type="submit"', false) // Login button should be present
        ;
    }

    /**
     * Register should be available
     */
    public function register(): void
    {
        get(route($this->auth->route_register))
            ->assertOk() // Should be 200
            ->assertSee('name="name"', false) // Name field should be present
            ->assertSee('name="email"', false) // Email field should be present
            ->assertSee('name="password"', false) // Password field should be present
            ->assertSee('name="password_confirmation"', false) // Confirm Password field should be present
            ->assertSee('type="submit"', false) // Register button should be present
        ;
    }

    /**
     * Forgot password should be available
     */
    public function forgotPassword(): void
    {
        get(route($this->auth->route_password_request))
            ->assertOk() // Should be 200
            ->assertSee('name="email"', false) // Email field should be present
            ->assertSee('type="submit"', false) // Send Password Reset Link button should be present
        ;
    }

    /**
     * Reset password should be available
     */
    public function resetPassword(): void
    {
        get(route($this->auth->route_password_reset, ['token' => 'wrong']))
            ->assertOk() // Should be 200
            ->assertSee('name="email"', false) // Email field should be present
            ->assertSee('name="password"', false) // Password field should be present
            ->assertSee('name="password_confirmation"', false) // Confirm Password field should be present
            ->assertSee('type="submit"', false) // Send Password Reset Link button should be present
        ;
    }
}