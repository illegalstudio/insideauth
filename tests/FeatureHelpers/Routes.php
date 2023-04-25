<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Tests\FeatureHelpers\Contracts\Helper;

final class Routes extends Helper
{
    /**
     * The route to the dummy homepage is correct
     */
    public function toHomepage(): void
    {
        expect(
            route('homepage', [], false)
        )->toBe('/homepage');
    }

    /**
     * The route to the dummy dashboard is correct
     */
    public function toDashboard(): void
    {
        expect(
            route('dashboard', [], false)
        )->toBe('/dashboard');
    }

    /**
     * The route to login is correct
     */
    public function toLogin(): void
    {
        expect(
            route($this->auth->route_login, [], false)
        )->toBe('/' . $this->auth->name() . '/login');
    }

    /**
     * The route to logout is correct
     */
    public function toLogout(): void
    {
        expect(
            route($this->auth->route_logout, [], false)
        )->toBe('/' . $this->auth->name() . '/logout');
    }

    /**
     * The route to register is correct
     */
    public function toRegister(): void
    {
        expect(
            route($this->auth->route_register, [], false)
        )->toBe('/' . $this->auth->name() . '/register');
    }

    /**
     * The route to the password confirm is correct
     */
    public function toPasswordConfirm(): void
    {
        expect(
            route($this->auth->route_password_confirm, [], false)
        )->toBe('/' . $this->auth->name() . '/confirm-password');
    }

    /**
     * The route to the password request is correct
     */
    public function toPasswordRequest(): void
    {
        expect(
            route($this->auth->route_password_request, [], false)
        )->toBe('/' . $this->auth->name() . '/forgot-password');
    }

    /**
     * The route to the password email is correct
     */
    public function toPasswordEmail(): void
    {
        expect(
            route($this->auth->route_password_email, [], false)
        )->toBe('/' . $this->auth->name() . '/forgot-password');
    }

    /**
     * The route to the password update is correct
     */
    public function toPasswordUpdate(): void
    {
        expect(
            route($this->auth->route_password_update, [], false)
        )->toBe('/' . $this->auth->name() . '/password');
    }

    /**
     * The route to the password store is correct
     */
    public function toPasswordStore(): void
    {
        expect(
            route($this->auth->route_password_store, [], false)
        )->toBe('/' . $this->auth->name() . '/reset-password');
    }

    /**
     * The route to the password reset is correct
     */
    public function toPasswordReset(): void
    {
        expect(
            route($this->auth->route_password_reset, ['token' => 'test'], false)
        )->toBe('/' . $this->auth->name() . '/reset-password/test');
    }

    /**
     * The route to the profile edit is correct
     */
    public function toProfileEdit(): void
    {
        expect(
            route($this->auth->route_profile_edit, [], false)
        )->toBe('/' . $this->auth->name() . '/profile');
    }

    /**
     * The route to the profile update is correct
     */
    public function toProfileUpdate(): void
    {
        expect(
            route($this->auth->route_profile_update, [], false)
        )->toBe('/' . $this->auth->name() . '/profile');
    }

    /**
     * The route to the profile destroy is correct
     */
    public function toProfileDestroy(): void
    {
        expect(
            route($this->auth->route_profile_destroy, [], false)
        )->toBe('/' . $this->auth->name() . '/profile');
    }

    /**
     * The route to the verification send is correct
     */
    public function toVerificationSend(): void
    {
        expect(
            route($this->auth->route_verification_send, [], false)
        )->toBe('/' . $this->auth->name() . '/email/verification-notification');
    }

    /**
     * The route to the verification notice is correct
     */
    public function toVerificationNotice(): void
    {
        expect(
            route($this->auth->route_verification_notice, [], false)
        )->toBe('/' . $this->auth->name() . '/verify-email');
    }

    /**
     * The route to the verification verify is correct
     */
    public function toVerificationVerify(): void
    {
        expect(
            route($this->auth->route_verification_verify, ['id' => 1, 'hash' => 'test'], false)
        )->toBe('/' . $this->auth->name() . '/verify-email/1/test');
    }
}
