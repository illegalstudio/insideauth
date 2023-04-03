<?php

use Illegal\InsideAuth\InsideAuth;

beforeEach(
/**
 * @throws Exception
 */
    function () {
        $this->withoutVite();
        $this->bootAuth();
    }
);

/**
 * Check if the login is available
 */
it('exposes login', function () {
    $this->get(route($this->auth()->route_login))
        ->assertOk() // Should be 200
        ->assertSee('Email') // Email field should be present
        ->assertSee('Password') // Password field should be present
        ->assertSee('Forgot your password') // Login button should be present
        ->assertSee('Register here') // Register link should be present
    ;
});

/**
 * Check if the registration is available
 */
it('exposes register', function () {
    $this->get(route($this->auth()->route_register))
        ->assertOk() // Should be 200
        ->assertSee('Name') // Name field should be present
        ->assertSee('Email') // Email field should be present
        ->assertSee('Password') // Password field should be present
        ->assertSee('Confirm Password') // Confirm Password field should be present
        ->assertSee('Register') // Register button should be present
        ->assertSee('Already registered?') // Login link should be present
    ;
});

/**
 * Check if the forgot password is available
 */
it('exposes forgot password', function () {
    $this->get(route($this->auth()->route_password_request))
        ->assertOk() // Should be 200
        ->assertSee('No problem') // No problem text should be present
        ->assertSee('Email') // Email field should be present
        ->assertSee('Email Password Reset Link') // Send Password Reset Link button should be present
    ;
});

/**
 * Check that user profile is not available
 */
it('shouldn\'t expose profile', function () {
    $this->get(route($this->auth()->route_profile_edit))
        ->assertRedirect(route($this->auth()->route_login));

    $this->get(route($this->auth()->route_profile_update))
        ->assertRedirect(route($this->auth()->route_login));

    $this->get(route($this->auth()->route_profile_destroy))
        ->assertRedirect(route($this->auth()->route_login));
});

/**
 * Reset password page should be available, also with a wrong token
 */
it('should expose reset password', function () {
    $this->get(route($this->auth()->route_password_reset, ['token' => 'wrong']))
        ->assertOk() // Should be 200
        ->assertSee('Email') // Email field should be present
        ->assertSee('Password') // Password field should be present
        ->assertSee('Confirm Password') // Confirm Password field should be present
        ->assertSee('Reset Password') // Reset Password button should be present
    ;
});

/**
 * Verification notice should redirect to login
 */
it('should redirect verification notice to login', function () {
    $this->get(route($this->auth()->route_verification_notice))
        ->assertRedirect(route($this->auth()->route_login));
});

/**
 * Verification verify should redirect to login if id and hash are wrong
 */
it('should redirect verification verify to login if id and hash are wrong', function () {
    $this->get(route($this->auth()->route_verification_verify, ['id' => 1, 'hash' => 'wrong']))
        ->assertRedirect(route($this->auth()->route_login));
});