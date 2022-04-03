<?php

declare(strict_types=1);

namespace Dasher\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function authenticate($request, array $guards) : void
    {
        $guardName = \config('dasher.auth.guard');
        $guard     = $this->auth->guard($guardName);

        if ( ! $guard->check()) {
            $this->unauthenticated($request, $guards);

            return;
        }

        $this->auth->shouldUse($guardName);

        $user = $guard->user();

        if ($user instanceof DasherUser) {
            \abort_if( ! $user->canAccessFilament(), 403);

            return;
        }

        \abort_if(\config('app.env') !== 'local', 403);
    }

    protected function redirectTo($request) : string
    {
        return \route('filament.auth.login');
    }
}
