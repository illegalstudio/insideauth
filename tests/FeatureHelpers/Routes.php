<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Models\User;

final class Routes
{
    public function __construct(private readonly Authenticator $auth, private readonly ?User $user = null)
    {
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

    public function toLogout(): void
    {
        expect(
            route($this->auth->route_logout, [], false)
        )->toBe('/' . $this->auth->name() . '/logout');
    }

    public function toRegister(): void
    {
        expect(
            route($this->auth->route_register, [], false)
        )->toBe('/' . $this->auth->name() . '/register');
    }

    public function toPasswordConfirm(): void
    {
        expect(
            route($this->auth->route_password_confirm, [], false)
        )->toBe('/' . $this->auth->name() . '/confirm-password');
    }

    public function toPasswordRequest(): void
    {
        expect(
            route($this->auth->route_password_request, [], false)
        )->toBe('/' . $this->auth->name() . '/forgot-password');
    }

    public function toPasswordEmail(): void
    {
        expect(
            route($this->auth->route_password_email, [], false)
        )->toBe('/' . $this->auth->name() . '/forgot-password');
    }

    public function toPasswordUpdate(): void
    {
        expect(
            route($this->auth->route_password_update, [], false)
        )->toBe('/' . $this->auth->name() . '/password');
    }

    public function toPasswordStore(): void
    {
        expect(
            route($this->auth->route_password_store, [], false)
        )->toBe('/' . $this->auth->name() . '/reset-password');
    }

    public function toPasswordReset(): void
    {
        expect(
            route($this->auth->route_password_reset, ['token' => 'test'], false)
        )->toBe('/' . $this->auth->name() . '/reset-password/test');
    }

    public function toProfileEdit(): void
    {
        expect(
            route($this->auth->route_profile_edit, [], false)
        )->toBe('/' . $this->auth->name() . '/profile');
    }

    public function toProfileUpdate(): void
    {
        expect(
            route($this->auth->route_profile_update, [], false)
        )->toBe('/' . $this->auth->name() . '/profile');
    }

    public function toProfileDestroy(): void
    {
        expect(
            route($this->auth->route_profile_destroy, [], false)
        )->toBe('/' . $this->auth->name() . '/profile');
    }

    public function toVerificationSend(): void
    {
        expect(
            route($this->auth->route_verification_send, [], false)
        )->toBe('/' . $this->auth->name() . '/email/verification-notification');
    }

    public function toVerificationNotice(): void
    {
        expect(
            route($this->auth->route_verification_notice, [], false)
        )->toBe('/' . $this->auth->name() . '/verify-email');
    }

    public function toVerificationVerify(): void
    {
        expect(
            route($this->auth->route_verification_verify, ['id' => 1, 'hash' => 'test'], false)
        )->toBe('/' . $this->auth->name() . '/verify-email/1/test');
    }
}