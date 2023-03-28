<?php

namespace Illegal\InsideAuth\Passwords;

use Illegal\InsideAuth\InsideAuth;
use Illuminate\Auth\Passwords\PasswordBrokerManager as IlluminatePasswordBrokerManager;

class PasswordBrokerManager extends IlluminatePasswordBrokerManager
{
    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->broker(InsideAuth::getPasswordBrokerName('linky'))->{$method}(...$parameters);
    }
}
