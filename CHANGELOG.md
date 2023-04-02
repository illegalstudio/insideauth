# Unreleased

- Support for themes

# 0.2.7

Fixed the route of the profile destroy action.

# 0.2.6

Simplified route prefixing.

# 0.2.5

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
