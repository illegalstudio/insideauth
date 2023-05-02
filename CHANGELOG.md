# Unreleased

- Support for themes

# 0.2.13

**Release date**: 2023-05-02

- The getAuthenticator method can be called without name parameter, in this case the default name `auth` will be used.
- Added parameter with the list of all authentication classes used by insideauth (can be used to pass them to addPersistentMiddleware of livewire component)

# 0.2.12

**Release date**: 2023-04-25

- Added `homepage` parameter. The user will be redirect here after logout or delete account.
- Fixed some missing return types.
- The boot method can be called without name parameter, in this case the default name `auth` will be used.
- Added testing using pestphp.

# 0.2.11

**Release date**: 2023-04-02

Added enabled function, to enable / disable programmatically the entire auth routes collection

# 0.2.10

**Release date**: 2023-04-02

Changed main middlware name

# 0.2.9

**Release date**: 2023-04-02

Added insideauth_booted function

# 0.2.8

**Release date**: 2023-04-02

Fixed UserDelete event not being fired.

# 0.2.7

**Release date**: 2023-04-02

Fixed the route of the profile destroy action.

# 0.2.6

**Release date**: 2023-04-02

Simplified route prefixing.

# 0.2.5

**Release date**: 2023-04-02

Added parameters to all without* methods to allow for more flexibility.  
For example, you can now do:

```php
/**
 * Gather a configuration value from your application that determines 
 * whether or not registration is disabled.
 */
$disableRegistration = config('myapp.disable_registration');

/**
 * Pass the configuration value to the withoutRegistration method.
 */
insideauth()->withoutRegistration($disableRegistration);
```
