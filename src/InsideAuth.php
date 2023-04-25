<?php

namespace Illegal\InsideAuth;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin Builder
 */
class InsideAuth extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return Builder::class;
    }
}
