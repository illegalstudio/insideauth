<?php

namespace Illegal\InsideAuth\Registrators;

use Exception;
use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Contracts\RegistratorInterface;

/**
 * This class is the main class of the InsideAuth package.
 * It is used to register the routes, the middlewares, the config, etc.
 * It is used by the InsideAuth facade.
 *
 * @see \Illegal\InsideAuth\InsideAuth
 * @see RouteRegistrator
 * @see MiddlewareRegistrator
 * @see SecurityRegistrator
 *
 */
final class Registrator
{
    /**
     * This variable carries the authenticator for the current request.
     * It will be populate by the InjectIntoApplication middleware
     * @see \Illegal\InsideAuth\Http\Middleware\InjectIntoApplication
     */
    private Authenticator $current;

    /**
     * The registrators list, used to register the routes, the middlewares, the config, etc.
     */
    private array $registrators = [
        'security'   => SecurityRegistrator::class,
        'middleware' => MiddlewareRegistrator::class,
        'route'      => RouteRegistrator::class,
    ];

    /**
     * The list of the registered authenticators. Each time a new authenticator is registered,
     * via the boot method, it will be added to this list.
     */
    private array $authenticators = [];

    /**
     * Boot the components of the Auth set
     * @throws Exception
     */
    public function boot(string $name): Authenticator
    {

        /**
         * If already exists an authenticator with the same name, throw an exception.
         */
        if (filled($this->authenticators[$name] ?? null)) {
            throw new Exception("Authenticator $name already registered");
        }

        /**
         * 1. Build the authenticator
         * 2. Launch all the registrators
         * 3. Fill the authenticator parameters
         */
        $authenticator = new Authenticator($name);

        collect($this->registrators)
            ->map(function ($registrator, $category) use ($authenticator, $name) {
                return new $registrator($authenticator, $category);
            })->map(function (RegistratorInterface $registrator) {
                $registrator->boot();
            });

        /**
         * Push the authenticator to the list of the registered authenticators
         */
        $this->authenticators[$name] = $authenticator;

        return $authenticator;
    }

    /**
     * Get the authenticator by name
     */
    public function getAuthenticator(string $name): Authenticator
    {
        return $this->authenticators[$name] ?? abort(500, 'Authenticator not found');
    }

    /**
     * Set the current authenticator instance
     */
    public function withCurrent(Authenticator $authenticator): self
    {
        $this->current = $authenticator;
        return $this;
    }

    /**
     * Returns the current authenticator instance
     */
    public function current(): Authenticator
    {
        return $this->current;
    }
}
