<?php

namespace Illegal\InsideAuth\Contracts;

use Illegal\InsideAuth\Authenticator;

interface RegistratorInterface
{

    /**
     * @param Authenticator $authenticator The authenticator instance to be used.
     * @param string $prefix The prefix to be used for the parameters.
     */
    public function __construct(Authenticator $authenticator, string $prefix);

    /**
     * Magic getter for parameters
     */
    public function __get(string $key);

    /**
     * Set of actions to be performed when booting the auth.
     */
    public function boot(): void;
}
