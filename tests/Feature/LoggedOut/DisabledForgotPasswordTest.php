<?php

use function Pest\Laravel\{get};
use function Pest\Laravel\{withoutVite};

beforeEach(
/**
 * @throws Exception
 */
    function () {
        withoutVite();
        $this->bootAuth();
        $this->auth()->withoutForgotPassword();
    }
);

it('routes to dashboard', fn() => $this->routes->toDashboard());
it('routes to homepage', fn() => $this->routes->toHomepage());
it('routes to login', fn() => $this->routes->toLogin());
it('routes to logout', fn() => $this->routes->toLogout());
it('routes to register', fn() => $this->routes->toRegister());
it('routes to password confirm', fn() => $this->routes->toPasswordConfirm());
it('routes to password request', fn() => $this->routes->toPasswordRequest());
it('routes to password email', fn() => $this->routes->toPasswordEmail());
it('routes to password update', fn() => $this->routes->toPasswordUpdate());
it('routes to password store', fn() => $this->routes->toPasswordStore());
it('routes to password reset', fn() => $this->routes->toPasswordReset());
it('routes to profile edit', fn() => $this->routes->toProfileEdit());
it('routes to profile update', fn() => $this->routes->toProfileUpdate());
it('routes to profile destroy', fn() => $this->routes->toProfileDestroy());
it('routes to verification send', fn() => $this->routes->toVerificationSend());
it('routes to verification notice', fn() => $this->routes->toVerificationNotice());
it('routes to verification verify', fn() => $this->routes->toVerificationVerify());

/**
 * Check that the login, register are available
 */
it('exposes homepage', fn() => $this->exposes->homepage());
it('exposes login', fn() => $this->exposes->login());
it('exposes register', fn() => $this->exposes->register());

/**
 * Hides forgot and reset password
 */
it('hides forgot password', fn() => $this->hides->forgotPassword());
it('hides reset password', fn() => $this->hides->resetPassword());

/**
 * Has the correct links in the various views
 */
it('has register in login', fn() => $this->has->registerInLogin());
it('has login in register', fn() => $this->has->loginInRegister());

/**
 * Hasn't the link to forgot password in the login
 */

it('has not password request in login', fn() => $this->hasNot->passwordRequestInLogin());

/**
 * Correcly redirects to login protected routes
 */
it('redirects dashboard to login', fn() => $this->redirects->dashboardToLogin());
it('redirects verification notice to login', fn() => $this->redirects->verificatioNoticeToLogin());
it('redirects verification verify to login', fn() => $this->redirects->verificationVerifyToLogin());
it('redirects profile to login', fn() => $this->redirects->profileToLogin());
