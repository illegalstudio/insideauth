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

    /**
     * The dashboard - defined in the test suite - should not be available
     */
    public function dashboard(): void
    {
        $this->testCase()->get(route('dashboard'))
            ->assertNotFound() // Should be 404
        ;
    }

    /**
     * Hides the verification notice route
     */
    public function verificationNotice(): void
    {
        $this->testCase()->get(route($this->auth->route_verification_notice))
            ->assertNotFound() // Should be 404
        ;
    }

    /**
     * Hides the verification send route
     */
    public function verificationSend(): void
    {
        $this->testCase()->post(route($this->auth->route_verification_send))
            ->assertNotFound() // Should be 404
        ;
    }

    /**
     * Hides the verification verify route
     */
    public function verificationVerify(): void
    {
        $this->testCase()->get(route($this->auth->route_verification_verify, ['id' => 1, 'hash' => 'wrong']))
            ->assertNotFound() // Should be 404
        ;
    }
}