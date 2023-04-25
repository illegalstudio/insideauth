<?php

namespace Illegal\InsideAuth\Registrators;

use Illegal\InsideAuth\Contracts\AbstractRegistrator;
use Illegal\InsideAuth\Models\User;
use Illuminate\Config\Repository;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;

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
     * The database prefix to use for the tables
     */
    protected string $dbPrefix;

    public function __construct(Repository $config, Router $router, string $dbPrefix = "")
    {
        $this->dbPrefix = $dbPrefix;
        parent::__construct($config, $router);
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
    public function collectAndMergeParameters(): Collection
    {
        $this->parameters = collect([
            'guard'           => $this->authName,
            'provider'        => $this->authName,
            'password_broker' => $this->authName,
        ]);

        return $this->parameters->mapWithKeys(fn($value, $key) => [$this->prefix . '_' . $key => $value]);
    }

    /**
     * @inheritDoc
     */
    public function boot(Collection $allParameters): void
    {
        /**
         * Configure the guard
         */
        $this->config->set('auth.guards.' . $this->guard, [
            'driver'   => 'session',
            'provider' => $this->provider
        ]);

        /**
         * Configure the provider
         */
        $this->config->set('auth.providers.' . $this->provider, [
            'driver' => 'eloquent',
            'model'  => User::class
        ]);

        /**
         * Configure the password broker
         */
        $this->config->set('auth.passwords.' . $this->password_broker, [
            'provider' => $this->provider,
            'table'    => $this->dbPrefix . 'password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ]);
    }
}
