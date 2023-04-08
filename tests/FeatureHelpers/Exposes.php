<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Tests\FeatureHelpers\Contracts\Helper;

final class Exposes extends Helper
{

    /**
     * The dummy dashboard - defined in the test suite - should be available
     */
    public function dashboard(): void
    {
        $this->testCase()->get(route('dashboard'))
            ->assertOk()
            ->assertSee('This is a dummy dashboard');
    }

    /**
     * Login should be available
     */
    public function login(): void
    {
        $this->testCase()->get(route($this->auth->route_login))
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
        $this->testCase()->get(route($this->auth->route_register))
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
        $this->testCase()->get(route($this->auth->route_password_request))
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
        $this->testCase()->get(route($this->auth->route_password_reset, ['token' => 'wrong']))
            ->assertOk() // Should be 200
            ->assertSee('name="email"', false) // Email field should be present
            ->assertSee('name="password"', false) // Password field should be present
            ->assertSee('name="password_confirmation"', false) // Confirm Password field should be present
            ->assertSee('type="submit"', false) // Send Password Reset Link button should be present
        ;
    }

    /**
     * The Verification verify page should return a 403 error
     */
    public function errorPageOnVerificationVerify(): void
    {
        $this->testCase()->get(route($this->auth->route_verification_verify, ['id' => 1, 'hash' => 'wrong']))
            ->assertStatus(403);
        ;
    }

    public function profile(): void
    {
        $this->testCase()->get(route($this->auth->route_profile_edit))
            ->assertOk() // Should be 200
            ->assertSee('name="name"', false) // Name field should be present
            ->assertSee('name="email"', false) // Email field should be present
            ->assertSee(route($this->auth->route_profile_update), false) // Update profile form should be present
            ->assertSee(route($this->auth->route_password_update), false) // Update password form should be present
            ->assertSee(route($this->auth->route_profile_destroy), false) // Delete profile form should be present
            ->assertSee(route($this->auth->route_logout), false) // Logout button should be present
        ;
    }
}