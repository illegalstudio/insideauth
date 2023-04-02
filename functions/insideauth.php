<?php

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\InsideAuth;

/**
 * This global function will return the current InsideAuth authenticator instance.
 * @see InsideAuth::current()
 * @see Authenticator
 */
function insideauth(): Authenticator
{
    return InsideAuth::current();
}

function insideauth_booted(): bool
{
    return InsideAuth::booted();
}
