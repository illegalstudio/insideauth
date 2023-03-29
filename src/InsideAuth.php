<?php

namespace Illegal\InsideAuth;

use Illegal\InsideAuth\Registrators\Registrator;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin Registrator
 */
class InsideAuth extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return Registrator::class;
    }
}
