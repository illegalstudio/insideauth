<?php

namespace Illegal\InsideAuth;

use Illuminate\Support\Collection;

/**
 * The Authenticator class is used to store the parameters of the auth.
 * It will be available during the lifecycle of the request.
 *
 * Params that are registered by RouteRegistrator:
 * @property string $route_login The name of the login route
 * @property string $route_register The name of the register route
 * @property string $route_password_request The name of the password request route
 * @property string $route_password_email The name of the password email route
 * @property string $route_password_reset The name of the password reset route
 * @property string $route_password_store The name of the password store route
 * @property string $route_logout The name of the logout route
 * @property string $route_verification_notice The name of the verification notice route
 * @property string $route_verification_verify The name of the verification verify route
 * @property string $route_verification_send The name of the verification send route
 * @property string $route_password_confirm The name of the password confirm route
 * @property string $route_password_update The name of the password update route
 * @property string $route_profile_edit The name of the profile edit route
 * @property string $route_profile_update The name of the profile update route
 * @property string $route_profile_destroy The name of the profile destroy route
 *
 * Params that are registered by MiddlewareRegistrator:
 * @property string $middleware_verified The name of the main middleware
 * @property string $middleware_guest The name of the guest middleware
 * @property string $middleware_logged_in The name of the logged in middleware
 * @property string $middleware_web The name of the web middleware
 *
 * Params that are registered by SecurityRegistrator:
 * @property string $security_guard The name of the guard
 * @property string $security_provider The name of the provider
 * @property string $security_password_broker The name of the password broker
 */
class Authenticator
{
    /**
     * The parameters collection
     */
    public Collection $parameters;

    /**
     * The name of the dashboard route, we'll use this to redirect the user to the dashboard after login
     */
    private ?string $dashboard = null;

    /**
     * Should the registration be enabled?
     */
    private bool $registrationEnabled = true;

    /**
     * Should the forgot password be enabled?
     */
    private bool $forgotPasswordEnabled = true;

    /**
     * Should the email verification be enabled?
     */
    private bool $emailVerificationEnabled = true;

    /**
     * Should the user profile be enabled?
     */
    private bool $userProfileEnabled = true;

    /**
     * Construct the Authenticator, building the parameters collection
     *
     * @param string $name The name of the auth
     */
    public function __construct(private readonly string $name)
    {
        $this->parameters = Collection::make();
    }

    /**
     * Get the name of the auth
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Set the dashboard route
     */
    public function withDashboard(string $dashboard): static
    {
        $this->dashboard = $dashboard;

        return $this;
    }

    /**
     * Get the dashboard route
     */
    public function dashboard(): ?string
    {
        return $this->dashboard;
    }

    /**
     * Merge parameters into the parameters collection
     */
    public function merge(array|Collection $parameters): static
    {
        $this->parameters = $this->parameters->merge($parameters);

        return $this;
    }

    /**
     * Magic method to get a variable from the parameters collection
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Returns a variable from the parameters collection
     */
    public function get($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * Toggle the registration
     */
    public function toggleRegistration(bool $enabled): self
    {
        $this->registrationEnabled = $enabled;
        return $this;
    }

    /**
     * Disable the registration
     */
    public function withoutRegistration(): self
    {
        return $this->toggleRegistration(false);
    }

    /**
     * Is the registration enabled?
     */
    public function isRegistrationEnabled(): bool
    {
        return $this->registrationEnabled;
    }

    /**
     * Toggle the forgot password
     */
    public function toggleForgotPassword(bool $enabled): self
    {
        $this->forgotPasswordEnabled = $enabled;
        return $this;
    }

    /**
     * Disable the forgot password
     */
    public function withoutForgotPassword(): self
    {
        return $this->toggleForgotPassword(false);
    }

    /**
     * Is the forgot password enabled?
     */
    public function isForgotPasswordEnabled(): bool
    {
        return $this->forgotPasswordEnabled;
    }

    /**
     * Toggle the email verification
     */
    public function toggleEmailVerification(bool $enabled): self
    {
        $this->emailVerificationEnabled = $enabled;
        return $this;
    }

    /**
     * Disable the email verification
     */
    public function withoutEmailVerification(): self
    {
        return $this->toggleEmailVerification(false);
    }

    /**
     * Is the email verification enabled?
     */
    public function isEmailVerificationEnabled(): bool
    {
        return $this->emailVerificationEnabled;
    }

    /**
     * Toggle the user profile
     */
    public function toggleUserProfile(bool $enabled): self
    {
        $this->userProfileEnabled = $enabled;
        return $this;
    }

    /**
     * Disable the user profile
     */
    public function withoutUserProfile(): self
    {
        return $this->toggleUserProfile(false);
    }

    /**
     * Is the user profile enabled?
     */
    public function isUserProfileEnabled(): bool
    {
        return $this->userProfileEnabled;
    }
}
