# Laravel InsideAuth

_THIS REPOSITORY IS UNDER ACTIVE DEVELOPMENT AND IS NOT READY FOR PRODUCTION USE._

Laravel InsideAuth is a seamless side authentication solution for Laravel packages. It provides an easy and independent authentication mechanism, allowing developers to focus on building core features of their packages or applications without worrying about managing separate authentication systems.

Features
1. **Easy Integration**: Quickly integrate InsideAuth into your existing Laravel packages or applications with just a few lines of code.
2. **Independent Authentication**: InsideAuth creates a separate authentication system for your Laravel package, ensuring it doesn't interfere with your main application's authentication.
3. **Customizable**: Tailor the authentication process to your specific needs with various configuration options for login, registration, and password reset processes.
4. **Secure**: InsideAuth follows industry-standard security practices and utilizes the latest encryption methods to protect your application from unauthorized access.
5. **Middleware Support**: Easily restrict access to specific routes based on a user's authentication status with middleware support.

# Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Middleware](#middleware)
- [Customization](#customization)
- [Security](#security)
- [License](#license)

# Installation

You can effortlessly install the package using Composer:

```bash
composer require illegal/insideauth
```

Once installed, it's necessary to migrate the database tables in order to set up the default tables for the package.

InsideAuth employs a prefix for the tables, which can be customized as needed. Refer to the [Configuration](#configuration) section for further details.

```bash
php artisan migrate
```

# Configuration

By default, InsideAuth applies the `auth_` prefix to the database tables. To customize this prefix, simply add the `INSIDEAUTH_TABLE_PREFIX` variable to your `.env` file.

```dotenv
INSIDE_AUTH_DB_PREFIX="myprefix_"
```

# Usage

You can create a new authentication set by utilizing the InsideAuth facade, which returns an instance of the Authenticator class. This class encompasses all the configuration settings for the authentication set.

Generally, it's recommended to register the authentication set within the `boot` method of your service provider.

```php
InsideAuth::boot('myproject');
```

InsideAuth takes care of automatically registering the routes for the authentication set.

The routes are prefixed with the name of the authentication set; in this case, they will be prefixed with `myproject`.

```text
/myproject/login
/myproject/register
...
```

You can obtain the routes for all functionalities by employing parameters on the Authenticator class.

For further details, refer to the [Parameters](#parameters) section.

# Middleware

InsideAuth comes with a collection of middlewares.

These middlewares enable you to control access to specific routes depending on a user's authentication status.

You can acquire middleware for all functionalities by utilizing parameters on the Authenticator class.

For more information, consult the [Parameters](#parameters) section.

# Customization

Upon registering a new authentication set, you can make use of the available functions on the Authenticator class to tailor the authentication process to your requirements.

Listed below are all the functions that can be utilized to customize the authentication process:

```php
\Illegal\InsideAuth\InsideAuth::boot('myproject')
    ->withoutRegister()
    ->withoutForgotPassword()
    ->withoutEmailVerification()
    ->withoutUserProfile()
    ->withDashboard('my_dashboard_route')
    ->withConfirmPasswordTemplate('my_confirm_password_template')
    ->withForgotPasswordTemplate('my_forgot_password_template')
    ->withLoginTemplate('my_login_template')
    ->withRegisterTemplate('my_register_template')
    ->withResetPasswordTemplate('my_reset_password_template')
    ->withVerifyEmailTemplate('my_verify_email_template')
    ->withProfileEditTemplate('my_profile_edit_template');

```

# Parameters

Typically, you will need to access the routes and middlewares for the authentication set throughout the lifespan of your application.

There are several methods for accessing the current Authenticator instance.

**Requesting a specific Authenticator instance**

```php
$auth = InsideAuth::getAuthenticator('myproject');
```

`myproject` is the name of the authentication set you provided to the boot method.

Since the current authenticator is injected though a middleware, this
is the only method to access the current authenticator before the requests starts. For
example in a Service Provider.

This is the typical method you will use to protect your routes.

```php
Route::middleware([
    InsideAuth::getAuthenticator('myproject')->middleware_web,
    InsideAuth::getAuthenticator('myproject')->middleware_verified,
])->group(function () {
    // ...
});
```

**Via the InsideAuth facade**

```php
$auth = InsideAuth::current();
```

**Via the injection in the `Request` attributes**

```php
$auth = $request->attributes->get('authenticator');
```

**Via the provided helper function**

```php
$auth = insideauth();
```

The simplest and recommended approach for accessing the current Authenticator instance in your blade templates is via the helper function.

## Complete list of parameters

```php

// Flags
$auth->registration_enabled         // Whether registration is enabled
$auth->forgot_password_enabled      // Whether forgot password is enabled
$auth->email_verification_enabled   // Whether email verification is enabled
$auth->user_profile_enabled         // Whether the user profile is enabled
 
// Templates
$auth->template_confirm_password    // The name of the confirm password template
$auth->template_forgot_password     // The name of the forgot password template
$auth->template_login               // The name of the login template
$auth->template_register            // The name of the register template
$auth->template_reset_password      // The name of the reset password template
$auth->template_verify_email        // The name of the verify email template
$auth->template_profile_edit        // The name of the profile edit template

// Routes
$auth->dashboard                    // The name of the dashboard route
$auth->route_login                  // The name of the login route
$auth->route_register               // The name of the register route
$auth->route_password_request       // The name of the password request route
$auth->route_password_email         // The name of the password email route
$auth->route_password_reset         // The name of the password reset route
$auth->route_password_store         // The name of the password store route
$auth->route_logout                 // The name of the logout route
$auth->route_verification_notice    // The name of the verification notice route
$auth->route_verification_verify    // The name of the verification verify route
$auth->route_verification_send      // The name of the verification send route
$auth->route_password_confirm       // The name of the password confirm route
$auth->route_password_update        // The name of the password update route
$auth->route_profile_edit           // The name of the profile edit route
$auth->route_profile_update         // The name of the profile update route
$auth->route_profile_destroy        // The name of the profile destroy route

// Middlewares
$auth->middleware_verified          // The name of the main middleware
$auth->middleware_guest             // The name of the guest middleware
$auth->middleware_logged_in         // The name of the logged in middleware
$auth->middleware_web               // The name of the web middleware

// Security
$auth->security_guard               // The name of the guard
$auth->security_provider            // The name of the provider
$auth->security_password_broker     // The name of the password broker
```

# Security

To secure your routes, simply apply the appropriate middlewares provided by InsideAuth.

```php
Route::middleware(insideauth()->middleware_verified)->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});
```

It's crucial that your routes use the standard `web` middleware.

If you do not already have this middleware included, you can make use of the one provided by InsideAuth.

```php
Route::middleware([insideauth()->middleware_web, insideauth()->middleware_verified])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});
```

# License

Laravel InsideAuth is open-sourced software licensed under the [MIT license](LICENSE).
