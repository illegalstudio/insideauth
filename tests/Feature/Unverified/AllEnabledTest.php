<?php

use function Pest\Laravel\{withoutVite};

beforeEach(
/**
 * @throws Exception
 */
    function () {
        withoutVite();
        $this->bootAuth();
        $this->auth()->withDashboard('dashboard');
    }
);

/**
 * All routes are available
 */
it('routes to dashboard',               fn() => $this->routes->toDashboard());
it('routes to login',                   fn() => $this->routes->toLogin());
it('routes to logout',                  fn() => $this->routes->toLogout());
it('routes to register',                fn() => $this->routes->toRegister());
it('routes to password confirm',        fn() => $this->routes->toPasswordConfirm());
it('routes to password request',        fn() => $this->routes->toPasswordRequest());
it('routes to password email',          fn() => $this->routes->toPasswordEmail());
it('routes to password update',         fn() => $this->routes->toPasswordUpdate());
it('routes to password store',          fn() => $this->routes->toPasswordStore());
it('routes to password reset',          fn() => $this->routes->toPasswordReset());
it('routes to profile edit',            fn() => $this->routes->toProfileEdit());
it('routes to profile update',          fn() => $this->routes->toProfileUpdate());
it('routes to profile destroy',         fn() => $this->routes->toProfileDestroy());
it('routes to verification send',       fn() => $this->routes->toVerificationSend());
it('routes to verification notice',     fn() => $this->routes->toVerificationNotice());
it('routes to verification verify',     fn() => $this->routes->toVerificationVerify());

/**
 * Redirects the authenticated user to the dashboard
 */
it('redirects login to dashboard',                  fn() => $this->redirects->loginToDashboard());
it('redirects register to dashboard',               fn() => $this->redirects->registerToDashboard());
it('redirects forgot password to dashboard',        fn() => $this->redirects->forgotPasswordToDashboard());
it('redirects reset password to dashboard',         fn() => $this->redirects->resetPasswordToDashboard());
it('redirects profile to verification notice',      fn() => $this->redirects->profileToVerificationNotice());
it('redirects dashboard to verification notice',    fn() => $this->redirects->dashboardToVerificationNotice());

/**
 * Exposes the correct functionalities
 */
it('exposes verification notice',           fn() => $this->exposes->verificatioNotice());
it('exposes error on verification verify',  fn() => $this->exposes->errorPageOnVerificationVerify());
