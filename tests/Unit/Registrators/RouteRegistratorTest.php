<?php

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
use Illegal\InsideAuth\Registrators\RouteRegistrator;
use Illuminate\Config\Repository;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Collection;

test('route registrator handles boot correctly', function ($authName) {

    $config = Mockery::mock(Repository::class);
    $router = Mockery::mock(Router::class);
    $router->shouldAllowMockingProtectedMethods();

    $routeRegistrar = Mockery::mock(RouteRegistrar::class);

    $routeRegistrator = ( new RouteRegistrator($config, $router) )
        ->withAuthName($authName);

    $parameters = $routeRegistrator->collectAndMergeParameters();

    expect($parameters)->toBeInstanceOf(Collection::class)
        ->and($parameters->toArray())->toMatchArray([
            'route_login'               => $authName . '.auth.login',
            'route_register'            => $authName . '.auth.register',
            'route_password_request'    => $authName . '.auth.password.request',
            'route_password_email'      => $authName . '.auth.password.email',
            'route_password_reset'      => $authName . '.auth.password.reset',
            'route_password_store'      => $authName . '.auth.password.store',
            'route_logout'              => $authName . '.auth.logout',
            'route_verification_notice' => $authName . '.auth.verification.notice',
            'route_verification_verify' => $authName . '.auth.verification.verify',
            'route_verification_send'   => $authName . '.auth.verification.send',
            'route_password_confirm'    => $authName . '.auth.password.confirm',
            'route_password_update'     => $authName . '.auth.password.update',
            'route_profile_edit'        => $authName . '.auth.profile.edit',
            'route_profile_update'      => $authName . '.auth.profile.update',
            'route_profile_destroy'     => $authName . '.auth.profile.destroy'
        ]);

    $router->shouldReceive('getLastGroupPrefix')->andReturn($routeRegistrar);
    $routeRegistrar->shouldReceive('prefix')->andReturn($routeRegistrar);
    $routeRegistrar->shouldReceive('group')->with(Mockery::on(function ($callable) {
        /**
         * Call the internal implementation
         */
        if (is_callable($callable)) {
            $callable();
            return true;
        }
        return false;
    }))->andReturn($routeRegistrar);
    $routeRegistrar->shouldReceive('name')->andReturn($routeRegistrar);

    $routeRegistrar->shouldReceive('middleware')->andReturn($routeRegistrar);

    $router->shouldReceive('middleware')->withArgs([
        'test_middleware_web'
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('middleware')->withArgs([
        'test_middleware_guest'
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('middleware')->withArgs([
        'test_middleware_logged_in'
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('middleware')->withArgs([
        'test_middleware_verified'
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('middleware')->withArgs([
        EnsureRegistrationIsEnabled::class
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('middleware')->withArgs([
        EnsureForgotPasswordIsEnabled::class
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('middleware')->withArgs([
        EnsureEmailVerificationIsEnabled::class
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('middleware')->withArgs([
        EnsureUserProfileIsEnabled::class
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('get')->withArgs([
        'login',
        [AuthenticatedSessionController::class, 'create']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('post')->withArgs([
        'login',
        [AuthenticatedSessionController::class, 'store']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('get')->withArgs([
        'register',
        [RegisteredUserController::class, 'create']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('post')->withArgs([
        'register',
        [RegisteredUserController::class, 'store']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('get')->withArgs([
        'forgot-password',
        [PasswordResetLinkController::class, 'create']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('post')->withArgs([
        'forgot-password',
        [PasswordResetLinkController::class, 'store']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('get')->withArgs([
        'reset-password/{token}',
        [NewPasswordController::class, 'create']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('post')->withArgs([
        'reset-password',
        [NewPasswordController::class, 'store']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('post')->withArgs([
        'logout',
        [AuthenticatedSessionController::class, 'destroy']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('get')->withArgs([
        'verify-email',
        EmailVerificationPromptController::class
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('get')->withArgs([
        'verify-email/{id}/{hash}',
        VerifyEmailController::class
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('post')->withArgs([
        'email/verification-notification',
        [EmailVerificationNotificationController::class, 'store']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('get')->withArgs([
        'confirm-password',
        [ConfirmablePasswordController::class, 'show']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('post')->withArgs([
        'confirm-password',
        [ConfirmablePasswordController::class, 'store']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('put')->withArgs([
        'password',
        [PasswordController::class, 'update']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('get')->withArgs([
        '/profile',
        [ProfileController::class, 'edit']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('patch')->withArgs([
        '/profile',
        [ProfileController::class, 'update']
    ])->once()->andReturn($routeRegistrar);

    $router->shouldReceive('delete')->withArgs([
        '/profile',
        [ProfileController::class, 'destroy']
    ])->once()->andReturn($routeRegistrar);

    $routeRegistrator->boot(collect([
        'middleware_web'       => 'test_middleware_web',
        'middleware_guest'     => 'test_middleware_guest',
        'middleware_logged_in' => 'test_middleware_logged_in',
        'middleware_verified'  => 'test_middleware_verified',
    ]));

})->with(['auth', 'test_auth', 'test-auth']);
