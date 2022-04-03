<?php

declare(strict_types=1);

namespace Dasher\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Dasher\Events\ServingFilament;

class DispatchServingFilamentEvent
{
    public function handle(Request $request, Closure $next)
    {
        ServingFilament::dispatch();

        return $next($request);
    }
}
