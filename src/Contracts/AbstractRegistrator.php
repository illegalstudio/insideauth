<?php

namespace Illegal\InsideAuth\Contracts;

use Illegal\InsideAuth\Authenticator;
use Illuminate\Config\Repository;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;

abstract class AbstractRegistrator
{
    /**
     * @var string The prefix to be used for the parameters
     */
    protected string $prefix = "";

    /**
     * @var string The authName that the registrators should use.
     */
    protected string $authName = "auth";

    public function __construct(protected readonly Repository $config, protected readonly Router $router)
    {
    }

    /**
     * Magic getter for parameters
     */
    public abstract function __get(string $key);

    /**
     * Setter for the authName
     */
    public function withAuthName($authName): static
    {
        $this->authName = $authName;

        return $this;
    }

    /**
     * This function will collect and merge all parameters inside the provided Authenticator
     * @see Authenticator::merge()
     */
    public abstract function collectAndMergeParameters(): Collection;

    /**
     * Set of actions to be performed when booting the auth.
     * @param Collection $allParameters All the parameters gathered by the authentication system
     */
    public abstract function boot(Collection $allParameters): void;
}
