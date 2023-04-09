<?php

namespace Illegal\InsideAuth\Registrators;

use Illegal\InsideAuth\Contracts\AbstractRegistrator;
use Illegal\InsideAuth\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

/**
 * @property string $guard
 * @property string $provider
 * @property string $password_broker
 */
class SecurityRegistrator extends AbstractRegistrator
{
    /**
     * The parameters collection, this will be merged inside the Authenticator, using the prefix
     */
    private Collection $parameters;

    /**
     * @inheritdoc
     */
    protected string $prefix = 'security';

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
    public function collectAndMergeParameters(): static
    {
        $this->parameters = collect([
            'guard'           => $this->authenticator->name(),
            'provider'        => $this->authenticator->name(),
            'password_broker' => $this->authenticator->name(),
        ]);

        $this->authenticator->merge($this->parameters->mapWithKeys(fn($value, $key) => [$this->prefix . '_' . $key => $value]));

        return $this;
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
            'table'    => config('inside_auth.db.prefix') . 'password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ]);
    }
}
