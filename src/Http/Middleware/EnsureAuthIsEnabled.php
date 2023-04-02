<?php

namespace Illegal\InsideAuth\Http\Middleware;

use Closure;
use Illegal\InsideAuth\InsideAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthIsEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!InsideAuth::current()->enabled) {
            abort(404);
        }

        return $next($request);
    }

}
