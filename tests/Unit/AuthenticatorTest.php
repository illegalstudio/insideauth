<?php

use Illegal\InsideAuth\Authenticator;
use Illuminate\Support\Collection;

beforeEach(function () {
    /** @noinspection PhpDynamicFieldDeclarationInspection */
    $this->authenticator = new Authenticator('auth');
});

test('The authenticator has the correct class', function () {
    expect($this->authenticator)->toBeInstanceOf(Authenticator::class);
});

test('Parameters are a Collection', function () {
    expect($this->authenticator->parameters)->toBeInstanceOf(Collection::class);
});

test('Expect the correct number of parameters', function () {
    expect($this->authenticator->parameters->count())->toBe(13);
});

test('The authenticator defaults are correct', function () {
    expect($this->authenticator->enabled)->toBeTrue()
        ->and($this->authenticator->registration_enabled)->toBeTrue()
        ->and($this->authenticator->forgot_password_enabled)->toBeTrue()
        ->and($this->authenticator->email_verification_enabled)->toBeTrue()
        ->and($this->authenticator->user_profile_enabled)->toBeTrue()
        ->and($this->authenticator->dashboard)->toBeNull()
        ->and($this->authenticator->template_confirm_password)->toBe('inside_auth::auth.confirm-password')
        ->and($this->authenticator->template_forgot_password)->toBe('inside_auth::auth.forgot-password')
        ->and($this->authenticator->template_login)->toBe('inside_auth::auth.login')
        ->and($this->authenticator->template_register)->toBe('inside_auth::auth.register')
        ->and($this->authenticator->template_reset_password)->toBe('inside_auth::auth.reset-password')
        ->and($this->authenticator->template_verify_email)->toBe('inside_auth::auth.verify-email')
        ->and($this->authenticator->template_profile_edit)->toBe('inside_auth::profile.edit');
});

test('The name of the authenticator is correct', function () {
    expect($this->authenticator->name())->toBe('auth');
});

test('The authenticator can be disabled', function () {
    expect()
        ->and($this->authenticator->enabled(false))->toBe($this->authenticator)
        ->and($this->authenticator->enabled)->toBeFalse()
        ->and($this->authenticator->enabled())->toBe($this->authenticator)
        ->and($this->authenticator->enabled)->toBeTrue();
});

test('The registration can be disabled', function () {
    expect()
        ->and($this->authenticator->withoutRegistration())->toBe($this->authenticator)
        ->and($this->authenticator->registration_enabled)->toBeFalse()
        ->and($this->authenticator->withoutRegistration(false))->toBe($this->authenticator)
        ->and($this->authenticator->registration_enabled)->toBeTrue();
});

test('The forgot password can be disabled', function () {
    expect()
        ->and($this->authenticator->withoutForgotPassword())->toBe($this->authenticator)
        ->and($this->authenticator->forgot_password_enabled)->toBeFalse()
        ->and($this->authenticator->withoutForgotPassword(false))->toBe($this->authenticator)
        ->and($this->authenticator->forgot_password_enabled)->toBeTrue();
});

test('The email verification can be disabled', function () {
    expect()
        ->and($this->authenticator->withoutEmailVerification())->toBe($this->authenticator)
        ->and($this->authenticator->email_verification_enabled)->toBeFalse()
        ->and($this->authenticator->withoutEmailVerification(false))->toBe($this->authenticator)
        ->and($this->authenticator->email_verification_enabled)->toBeTrue();
});

test('The user profile can be disabled', function () {
    expect()
        ->and($this->authenticator->withoutUserProfile())->toBe($this->authenticator)
        ->and($this->authenticator->user_profile_enabled)->toBeFalse()
        ->and($this->authenticator->withoutUserProfile(false))->toBe($this->authenticator)
        ->and($this->authenticator->user_profile_enabled)->toBeTrue();
});

test('The dashboard can be set', function () {
    expect()
        ->and($this->authenticator->withDashboard('dashboard'))->toBe($this->authenticator)
        ->and($this->authenticator->dashboard)->toBe('dashboard');
});

test('The confirm password template can be set', function () {
    expect()
        ->and($this->authenticator->withConfirmPasswordTemplate('confirm-password'))->toBe($this->authenticator)
        ->and($this->authenticator->template_confirm_password)->toBe('confirm-password');
});

test('The forgot password template can be set', function () {
    expect()
        ->and($this->authenticator->withForgotPasswordTemplate('forgot-password'))->toBe($this->authenticator)
        ->and($this->authenticator->template_forgot_password)->toBe('forgot-password');
});

test('The login template can be set', function () {
    expect()
        ->and($this->authenticator->withLoginTemplate('login'))->toBe($this->authenticator)
        ->and($this->authenticator->template_login)->toBe('login');
});

test('The register template can be set', function () {
    expect()
        ->and($this->authenticator->withRegisterTemplate('register'))->toBe($this->authenticator)
        ->and($this->authenticator->template_register)->toBe('register');
});

test('The reset password template can be set', function () {
    expect()
        ->and($this->authenticator->withResetPasswordTemplate('reset-password'))->toBe($this->authenticator)
        ->and($this->authenticator->template_reset_password)->toBe('reset-password');
});

test('The verify email template can be set', function () {
    expect()
        ->and($this->authenticator->withVerifyEmailTemplate('verify-email'))->toBe($this->authenticator)
        ->and($this->authenticator->template_verify_email)->toBe('verify-email');
});

test('The profile edit template can be set', function () {
    expect()
        ->and($this->authenticator->withProfileEditTemplate('profile-edit'))->toBe($this->authenticator)
        ->and($this->authenticator->template_profile_edit)->toBe('profile-edit');
});

test('Array properties can be merged', function () {
    expect()
        ->and($this->authenticator->merge(['test' => 'dummy']))->toBe($this->authenticator)
        ->and($this->authenticator->test)->toBe('dummy');
});