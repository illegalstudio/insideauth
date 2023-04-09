<?php

namespace Illegal\InsideAuth\Contracts;

use Illegal\InsideAuth\Authenticator;

abstract class AbstractRegistrator
{
    /**
     * @var Authenticator The authenticator instance to be used,
     */
    protected Authenticator $authenticator;

    /**
     * @var string The prefix to be used for the parameters
     */
    protected string $prefix;

    /**
     * Sets the Authenticator to be used
     */
    public function withAuthenticator(Authenticator $authenticator): static
    {
        $this->authenticator = $authenticator;

        return $this;
    }

    /**
     * Sets the prefix to be used for the parameters
     */
    public function withPrefix(string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Magic getter for parameters
     */
    public abstract function __get(string $key);

    /**
     * This function will collect and merge all parameters inside the provided Authenticator
     * @see Authenticator::merge()
     */
    public abstract function collectAndMergeParameters(): static;

    /**
     * Set of actions to be performed when booting the auth.
     */
    public abstract function boot(): void;
}
