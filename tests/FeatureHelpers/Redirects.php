<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Tests\FeatureHelpers\Contracts\Helper;
use function Pest\Laravel\get;

final class Redirects extends Helper
{
    /**
     * The dashboard should redirect to login
     */
    public function dashboardToLogin(): void
    {
        $this->testCase()->get(route('dashboard'))
            ->assertRedirect(route($this->auth->route_login));
    }

    /**
     * The Verification notice should redirect to login
     */
    public function verificatioNoticeToLogin(): void
    {
        $this->testCase()->get(route($this->auth->route_verification_notice))
            ->assertRedirect(route($this->auth->route_login));
    }

    /**
     * The Verification verify should redirect to login if id and hash are wrong
     */
    public function verificationVerifyToLogin(): void
    {
        $this->testCase()->get(route($this->auth->route_verification_verify, ['id' => 1, 'hash' => 'wrong']))
            ->assertRedirect(route($this->auth->route_login));
    }

    /**
     * All profile routes should redirect to login
     */
    public function profileToLogin(): void
    {
        $this->testCase()->get(route($this->auth->route_profile_edit))
            ->assertRedirect(route($this->auth->route_login));
        $this->testCase()->get(route($this->auth->route_profile_update))
            ->assertRedirect(route($this->auth->route_login));
        $this->testCase()->get(route($this->auth->route_profile_destroy))
            ->assertRedirect(route($this->auth->route_login));
    }
    /**
     * The login should redirect to dashboard if logged in
     */
    public function loginToDashboard(): void
    {
        $this->testCase()->get(route($this->auth->route_login))
            ->assertRedirect(route('dashboard'));
    }

    /**
     * The register should redirect to dashboard if logged in
     */
    public function registerToDashboard(): void
    {
        $this->testCase()->get(route($this->auth->route_register))
            ->assertRedirect(route('dashboard'));
    }

    /**
     * The verification notice should redirect to dashboard if logged in
     */
    public function verificatioNoticeToDashboard(): void
    {
        $this->testCase()->get(route($this->auth->route_verification_notice))
            ->assertRedirect(route('dashboard'));
    }

    /**
     * The verification verify should redirect to dashboard if logged in
     */
    public function forgotPasswordToDashboard(): void
    {
        $this->testCase()->get(route($this->auth->route_password_request))
            ->assertRedirect(route('dashboard'));
    }

    /**
     * The reset password should redirect to dashboard if logged in
     */
    public function resetPasswordToDashboard(): void
    {
        $this->testCase()->get(route($this->auth->route_password_reset, ['token' => 'wrong']))
            ->assertRedirect(route('dashboard'));
    }

    /**
     * The dashboard should redirect to verification notice if not verified
     */
    public function dashboardToVerificationNotice(): void
    {
        $this->testCase()->get(route('dashboard'))
            ->assertRedirect(route($this->auth->route_verification_notice));
    }

    /**
     * The profile should redirect to verification notice if not verified
     */
    public function profileToVerificationNotice(): void
    {
        $this->testCase()->get(route($this->auth->route_profile_edit))
            ->assertRedirect(route($this->auth->route_verification_notice));
    }
}