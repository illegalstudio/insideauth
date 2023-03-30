<?php

namespace Illegal\InsideAuth;

use Illuminate\Support\Collection;

/**
 * The Authenticator class is used to store the parameters of the auth.
 * It will be available during the lifecycle of the request.
 *
 * Internal params:
 * @property string $dashboard The name of the dashboard route
 * @property string $registration_enabled Whether registration is enabled
 * @property string $forgot_password_enabled Whether forgot password is enabled
 * @property string $email_verification_enabled Whether email verification is enabled
 * @property string $user_profile_enabled Whether the user profile is enabled
 * @property string $template_confirm_password The name of the confirm password template
 * @property string $template_forgot_password The name of the forgot password template
 * @property string $template_login The name of the login template
 * @property string $template_register The name of the register template
 * @property string $template_reset_password The name of the reset password template
 * @property string $template_verify_email The name of the verify email template
 * @property string $template_profile_edit The name of the profile edit template
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
     * Construct the Authenticator, building the parameters collection
     *
     * @param string $name The name of the auth
     */
    public function __construct(private readonly string $name)
    {
        $this->parameters = Collection::make([
            'dashboard'                  => null,
            'registration_enabled'       => true,
            'forgot_password_enabled'    => true,
            'email_verification_enabled' => true,
            'user_profile_enabled'       => true,
            'template_confirm_password'  => 'inside_auth::auth.confirm-password',
            'template_forgot_password'   => 'inside_auth::auth.forgot-password',
            'template_login'             => 'inside_auth::auth.login',
            'template_register'          => 'inside_auth::auth.register',
            'template_reset_password'    => 'inside_auth::auth.reset-password',
            'template_verify_email'      => 'inside_auth::auth.verify-email',
            'template_profile_edit'      => 'inside_auth::profile.edit',
        ]);
    }

    /**
     * Get the name of the auth
     */
    public function name(): string
    {
        return $this->name;
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
     * Internal helpers to retrieve a vale from the collection
     */
    private function get($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * Internal helper to set a value inside the collection
     */
    private function set(string $key, mixed $value): static
    {
        $this->parameters->put($key, $value);

        return $this;
    }

    ###############################################
    # Helpers to set the some internal parameters #
    ###############################################

    /**
     * Set the dashboard route
     */
    public function withDashboard(string $dashboard): static
    {
        return $this->set('dashboard', $dashboard);
    }

    /**
     * Disable registration
     *
     * @param bool $without Whether to disable registration
     */
    public function withoutRegistration(bool $without = true): static
    {
        return $this->set('registration_enabled', !$without);
    }

    /**
     * Disable forgot password
     *
     * @param bool $without Whether to disable forgot password
     */
    public function withoutForgotPassword(bool $without = true): static
    {
        return $this->set('forgot_password_enabled', !$without);
    }

    /**
     * Disable email verification
     *
     * @param bool $without Whether to disable email verification
     */
    public function withoutEmailVerification(bool $without = true): static
    {
        return $this->set('email_verification_enabled', !$without);
    }

    /**
     * Disable user profile
     *
     * @param bool $without Whether to disable user profile
     */
    public function withoutUserProfile(bool $without = true): static
    {
        return $this->set('user_profile_enabled', !$without);
    }

    /**
     * Set the confirm password template
     */
    public function withConfirmPasswordTemplate(string $template): static
    {
        return $this->set('template_confirm_password', $template);
    }

    /**
     * Set the forgot password template
     */
    public function withForgotPasswordTemplate(string $template): static
    {
        return $this->set('template_forgot_password', $template);
    }

    /**
     * Set the login template
     */
    public function withLoginTemplate(string $template): static
    {
        return $this->set('template_login', $template);
    }

    /**
     * Set the register template
     */
    public function withRegisterTemplate(string $template): static
    {
        return $this->set('template_register', $template);
    }

    /**
     * Set the reset password template
     */
    public function withResetPasswordTemplate(string $template): static
    {
        return $this->set('template_reset_password', $template);
    }

    /**
     * Set the verify email template
     */
    public function withVerifyEmailTemplate(string $template): static
    {
        return $this->set('template_verify_email', $template);
    }

    /**
     * Set the profile edit template
     */
    public function withProfileEditTemplate(string $template): static
    {
        return $this->set('template_profile_edit', $template);
    }
}
