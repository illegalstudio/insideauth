<?php

use Illegal\InsideAuth\Models\User;
use Illegal\InsideAuth\Registrators\SecurityRegistrator;
use Illuminate\Config\Repository;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;

test('security registrators handles boot correctly', function ($authName) {

    $config              = Mockery::mock(Repository::class);
    $router              = Mockery::mock(Router::class);
    $securityRegistrator = ( new SecurityRegistrator($config, $router, $authName) )
        ->withAuthName($authName);

    $parameters = $securityRegistrator->collectAndMergeParameters();

    expect($parameters)->toBeInstanceOf(Collection::class)
        ->and($parameters->toArray())->toMatchArray([
            'security_guard'           => $authName,
            'security_provider'        => $authName,
            'security_password_broker' => $authName
        ]);

    $config->shouldReceive('set')->withArgs([
        'auth.guards.' . $authName,
        [
            'driver'   => 'session',
            'provider' => $authName
        ]
    ]);

    $config->shouldReceive('set')->withArgs([
        'auth.providers.' . $authName,
        [
            'driver' => 'eloquent',
            'model'  => User::class
        ]
    ]);

    $config->shouldReceive('set')->withArgs([
        'auth.passwords.' . $authName,
        [
            'provider' => $authName,
            'table'    => $authName . 'password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ]
    ]);

    $securityRegistrator->boot(collect([]));
})->with(['auth', 'test_auth', 'test-auth']);
