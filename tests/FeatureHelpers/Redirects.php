<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers;

use Illegal\InsideAuth\Authenticator;
use function Pest\Laravel\get;

final class Redirects
{
    public function __construct(private readonly Authenticator $auth)
    {
    }

    /**
     * The Verification notice should redirect to login
     */
    public function verificatioNoticeToLogin(): void
    {
        get(route($this->auth->route_verification_notice))
            ->assertRedirect(route($this->auth->route_login));
    }

    /**
     * The Verification verify should redirect to login if id and hash are wrong
     */
    public function verificationVerifyToLogin(): void
    {
        get(route($this->auth->route_verification_verify, ['id' => 1, 'hash' => 'wrong']))
            ->assertRedirect(route($this->auth->route_login));
    }

    /**
     * All profile routes should redirect to login
     */
    public function profileToLogin(): void
    {
        get(route($this->auth->route_profile_edit))
            ->assertRedirect(route($this->auth->route_login));
        get(route($this->auth->route_profile_update))
            ->assertRedirect(route($this->auth->route_login));
        get(route($this->auth->route_profile_destroy))
            ->assertRedirect(route($this->auth->route_login));
    }
}