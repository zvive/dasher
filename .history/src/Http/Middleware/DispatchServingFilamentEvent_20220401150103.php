<?php

namespace Dasher\Http\Middleware;

use Closure;
use Dasher\Events\ServingFilament;
use Illuminate\Http\Request;

class DispatchServingFilamentEvent
{
    public function handle(Request $request, Closure $next)
    {
        ServingFilament::dispatch();

        return $next($request);
    }
}
