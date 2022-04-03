<?php

declare(strict_types=1);

namespace Dasher\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Dasher\Events\ServingDasher;

class DispatchServingDasherEvent
{
    public function handle(Request $request, Closure $next)
    {
        ServingDasher::dispatch();

        return $next($request);
    }
}
