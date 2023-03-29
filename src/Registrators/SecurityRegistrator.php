<?php

namespace Illegal\InsideAuth\Registrators;

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Contracts\RegistratorInterface;
use Illegal\InsideAuth\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

/**
 * @property string $guard
 * @property string $provider
 * @property string $password_broker
 */
class SecurityRegistrator implements RegistratorInterface
{
    /**
     * The parameters collection, this will be merged inside the Authenticator, using the prefix
     */
    private Collection $parameters;

    /**
     * @inheritDoc
     */
    public function __construct(private readonly Authenticator $authenticator, string $prefix = 'security')
    {
        $this->parameters = collect([
            'guard'           => $this->authenticator->name(),
            'provider'        => $this->authenticator->name(),
            'password_broker' => $this->authenticator->name(),
        ]);

        $this->authenticator->merge($this->parameters->mapWithKeys(fn($value, $key) => [$prefix . '_' . $key => $value]));
    }

    /**
     * @inheritDoc
     */
    public function __get($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        /**
         * Configure the guard
         */
        Config::set('auth.guards.' . $this->guard, [
            'driver'   => 'session',
            'provider' => $this->provider
        ]);

        /**
         * Configure the provider
         */
        Config::set('auth.providers.' . $this->provider, [
            'driver' => 'eloquent',
            'model'  => User::class
        ]);

        /**
         * Configure the password broker
         */
        Config::set('auth.passwords.' . $this->password_broker, [
            'provider' => $this->provider,
            'table'    => config('linky.db.prefix') . 'password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ]);
    }
}
