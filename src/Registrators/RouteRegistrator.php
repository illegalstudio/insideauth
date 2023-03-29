<?php

namespace Illegal\InsideAuth\Registrators;

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Contracts\RegistratorInterface;
use Illegal\InsideAuth\Http\Controllers\AuthenticatedSessionController;
use Illegal\InsideAuth\Http\Controllers\ConfirmablePasswordController;
use Illegal\InsideAuth\Http\Controllers\EmailVerificationNotificationController;
use Illegal\InsideAuth\Http\Controllers\EmailVerificationPromptController;
use Illegal\InsideAuth\Http\Controllers\NewPasswordController;
use Illegal\InsideAuth\Http\Controllers\PasswordController;
use Illegal\InsideAuth\Http\Controllers\PasswordResetLinkController;
use Illegal\InsideAuth\Http\Controllers\RegisteredUserController;
use Illegal\InsideAuth\Http\Controllers\VerifyEmailController;
use Illegal\Linky\Http\Controllers\ProfileController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

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
class RouteRegistrator implements RegistratorInterface
{
    /**
     * The parameters collection, this will be merged inside the Authenticator, using the prefix
     */
    private Collection $parameters;

    /**
     * @inheritDoc
     */
    public function __construct(private readonly Authenticator $authenticator, string $prefix = 'route')
    {
        $this->parameters = collect([
            'login'               => $this->authenticator->name() . '.auth.login',
            'register'            => $this->authenticator->name() . '.auth.register',
            'password_request'    => $this->authenticator->name() . '.auth.password.request',
            'password_email'      => $this->authenticator->name() . '.auth.password.email',
            'password_reset'      => $this->authenticator->name() . '.auth.password.reset',
            'password_store'      => $this->authenticator->name() . '.auth.password.store',
            'logout'              => $this->authenticator->name() . '.auth.logout',
            'verification_notice' => $this->authenticator->name() . '.auth.verification.notice',
            'verification_verify' => $this->authenticator->name() . '.auth.verification.verify',
            'verification_send'   => $this->authenticator->name() . '.auth.verification.send',
            'password_confirm'    => $this->authenticator->name() . '.auth.password.confirm',
            'password_update'     => $this->authenticator->name() . '.auth.password.update',
            'profile_edit'        => $this->authenticator->name() . '.auth.profile.edit',
            'profile_update'      => $this->authenticator->name() . '.auth.profile.update',
            'profile_destroy'     => $this->authenticator->name() . '.auth.profile.destroy'
        ]);

        $this->authenticator->merge($this->parameters->mapWithKeys(fn($value, $key) => [$prefix . '_' . $key => $value]));
    }

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
    public function boot(): void
    {
        Route::prefix($this->authenticator->name() . '/auth')->middleware($this->authenticator->middleware_web)->group(function () {
            /**
             * Routes that are accessible to guests users
             */
            Route::middleware($this->authenticator->middleware_guest)->group(function () {
                $this->registerLoginRoutes();
                $this->registerRegisterRoutes();
                $this->registerForgotPasswordRoutes();
            });

            /**
             * Routes that are accessible to logged in users, verified or not
             */
            Route::middleware($this->authenticator->middleware_logged_in)->group(function () {
                $this->registerLogoutRoutes();
                $this->registerEmailVerificationRoutes();
            });

            /**
             * Routes that are accessible to logged in and verified users
             */
            Route::middleware($this->authenticator->middleware_verified)->group(function () {
                $this->registerProfileRoutes();
            });
        });
    }

    /**
     * Register the login routes
     */
    private function registerLoginRoutes(): void
    {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name($this->login);
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    }

    /**
     * Register the registration routes
     */
    private function registerRegisterRoutes(): void
    {
        if (config('linky.auth.functionalities.register')) {
            Route::get('register', [RegisteredUserController::class, 'create'])->name($this->register);
            Route::post('register', [RegisteredUserController::class, 'store']);
        }
    }

    /**
     * Register the forgot password routes
     */
    private function registerForgotPasswordRoutes(): void
    {
        if (config('linky.auth.functionalities.forgot_password')) {
            Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name($this->password_request);
            Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name($this->password_email);

            Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name($this->password_reset);
            Route::post('reset-password', [NewPasswordController::class, 'store'])->name($this->password_store);
        }
    }

    /**
     * Register the logout routes
     */
    private function registerLogoutRoutes(): void
    {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name($this->logout);
    }

    /**
     * Register the email verification routes
     */
    public function registerEmailVerificationRoutes(): void
    {
        if (config('linky.auth.functionalities.email_verification')) {

            Route::get('verify-email', EmailVerificationPromptController::class)->name($this->verification_notice);
            Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name($this->verification_verify);

            Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name($this->verification_send);

            Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name($this->password_confirm);
            Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

            Route::put('password', [PasswordController::class, 'update'])->name($this->password_update);
        }
    }

    /**
     * Register the profile routes
     */
    public function registerProfileRoutes(): void
    {
        if (config('linky.auth.functionalities.user_profile')) {
            Route::get('/profile', [ProfileController::class, 'edit'])->name($this->profile_edit);
            Route::patch('/profile', [ProfileController::class, 'update'])->name($this->profile_update);
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name($this->profile_destroy);
        }
    }
}
