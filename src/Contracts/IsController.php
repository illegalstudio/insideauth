<?php

namespace Illegal\InsideAuth\Contracts;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

trait IsController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
