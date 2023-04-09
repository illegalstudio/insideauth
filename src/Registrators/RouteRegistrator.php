<?php

namespace Illegal\InsideAuth\Registrators;

use Illegal\InsideAuth\Contracts\AbstractRegistrator;
use Illegal\InsideAuth\Http\Controllers\AuthenticatedSessionController;
use Illegal\InsideAuth\Http\Controllers\ConfirmablePasswordController;
use Illegal\InsideAuth\Http\Controllers\EmailVerificationNotificationController;
use Illegal\InsideAuth\Http\Controllers\EmailVerificationPromptController;
use Illegal\InsideAuth\Http\Controllers\NewPasswordController;
use Illegal\InsideAuth\Http\Controllers\PasswordController;
use Illegal\InsideAuth\Http\Controllers\PasswordResetLinkController;
use Illegal\InsideAuth\Http\Controllers\ProfileController;
use Illegal\InsideAuth\Http\Controllers\RegisteredUserController;
use Illegal\InsideAuth\Http\Controllers\VerifyEmailController;
use Illegal\InsideAuth\Http\Middleware\EnsureEmailVerificationIsEnabled;
use Illegal\InsideAuth\Http\Middleware\EnsureForgotPasswordIsEnabled;
use Illegal\InsideAuth\Http\Middleware\EnsureRegistrationIsEnabled;
use Illegal\InsideAuth\Http\Middleware\EnsureUserProfileIsEnabled;
use Illuminate\Support\Collection;

/**
 * @property string $login
 * @property string $register
 * @property string $password_request
 * @property string $password_email
 * @property string $password_reset
 * @property string $password_store
 * @property string $logout
 * @property string $verification_notice
 * @property string $verification_verify
 * @property string $verification_send
 * @property string $password_confirm
 * @property string $password_update
 * @property string $profile_edit
 * @property string $profile_update
 * @property string $profile_destroy
 */
class RouteRegistrator extends AbstractRegistrator
{
    /**
     * The parameters collection, this will be merged inside the Authenticator, using the prefix
     */
    private Collection $parameters;

    /**
     * @inheritdoc
     */
    protected string $prefix = 'route';

    /**
     * @inheritDoc
     */
    public function __get(string $key): string
    {
        return $this->parameters->get($key);
    }

    /**
     * @inheritDoc
     */
    public function collectAndMergeParameters(): Collection
    {
        $this->parameters = collect([
            'login'               => $this->authName . '.auth.login',
            'register'            => $this->authName . '.auth.register',
            'password_request'    => $this->authName . '.auth.password.request',
            'password_email'      => $this->authName . '.auth.password.email',
            'password_reset'      => $this->authName . '.auth.password.reset',
            'password_store'      => $this->authName . '.auth.password.store',
            'logout'              => $this->authName . '.auth.logout',
            'verification_notice' => $this->authName . '.auth.verification.notice',
            'verification_verify' => $this->authName . '.auth.verification.verify',
            'verification_send'   => $this->authName . '.auth.verification.send',
            'password_confirm'    => $this->authName . '.auth.password.confirm',
            'password_update'     => $this->authName . '.auth.password.update',
            'profile_edit'        => $this->authName . '.auth.profile.edit',
            'profile_update'      => $this->authName . '.auth.profile.update',
            'profile_destroy'     => $this->authName . '.auth.profile.destroy'
        ]);

        return $this->parameters->mapWithKeys(fn($value, $key) => [$this->prefix . '_' . $key => $value]);
    }

    /**
     * @inheritDoc
     */
    public function boot(Collection $allParameters): void
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->router->prefix($this->authName)->middleware($allParameters->get('middleware_web'))->group(function () use ($allParameters) {
            /**
             * Routes that are accessible to guests users
             */
            $this->router->middleware($allParameters->get('middleware_guest'))->group(function () {
                $this->registerLoginRoutes();
                $this->registerRegisterRoutes();
                $this->registerForgotPasswordRoutes();
            });

            /**
             * Routes that are accessible to logged in users, verified or not
             */
            $this->router->middleware($allParameters->get('middleware_logged_in'))->group(function () {
                $this->registerLogoutRoutes();
                $this->registerEmailVerificationRoutes();
            });

            /**
             * Routes that are accessible to logged in and verified users
             */
            $this->router->middleware($allParameters->get('middleware_verified'))->group(function () {
                $this->registerProfileRoutes();
            });
        });
    }

    /**
     * Register the login routes
     */
    private function registerLoginRoutes(): void
    {
        $this->router->get('login', [AuthenticatedSessionController::class, 'create'])->name($this->login);
        $this->router->post('login', [AuthenticatedSessionController::class, 'store']);
    }

    /**
     * Register the registration routes
     */
    private function registerRegisterRoutes(): void
    {
        $this->router->middleware(EnsureRegistrationIsEnabled::class)->group(function () {
            $this->router->get('register', [RegisteredUserController::class, 'create'])->name($this->register);
            $this->router->post('register', [RegisteredUserController::class, 'store']);
        });
    }

    /**
     * Register the forgot password routes
     */
    private function registerForgotPasswordRoutes(): void
    {
        $this->router->middleware(EnsureForgotPasswordIsEnabled::class)->group(function () {
            $this->router->get('forgot-password', [PasswordResetLinkController::class, 'create'])->name($this->password_request);
            $this->router->post('forgot-password', [PasswordResetLinkController::class, 'store'])->name($this->password_email);

            $this->router->get('reset-password/{token}', [NewPasswordController::class, 'create'])->name($this->password_reset);
            $this->router->post('reset-password', [NewPasswordController::class, 'store'])->name($this->password_store);
        });
    }

    /**
     * Register the logout routes
     */
    private function registerLogoutRoutes(): void
    {
        $this->router->post('logout', [AuthenticatedSessionController::class, 'destroy'])->name($this->logout);
    }

    /**
     * Register the email verification routes
     */
    public function registerEmailVerificationRoutes(): void
    {
        $this->router->middleware(EnsureEmailVerificationIsEnabled::class)->group(function () {
            $this->router->get('verify-email', EmailVerificationPromptController::class)->name($this->verification_notice);
            $this->router->get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name($this->verification_verify);

            $this->router->post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name($this->verification_send);

            $this->router->get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name($this->password_confirm);
            $this->router->post('confirm-password', [ConfirmablePasswordController::class, 'store']);

            $this->router->put('password', [PasswordController::class, 'update'])->name($this->password_update);
        });
    }

    /**
     * Register the profile routes
     */
    public function registerProfileRoutes(): void
    {
        $this->router->middleware(EnsureUserProfileIsEnabled::class)->group(function () {
            $this->router->get('/profile', [ProfileController::class, 'edit'])->name($this->profile_edit);
            $this->router->patch('/profile', [ProfileController::class, 'update'])->name($this->profile_update);
            $this->router->delete('/profile', [ProfileController::class, 'destroy'])->name($this->profile_destroy);
        });
    }
}
