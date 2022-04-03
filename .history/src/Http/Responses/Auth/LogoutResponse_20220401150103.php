<?php

namespace Dasher\Http\Responses\Auth;

use Dasher\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;

class LogoutResponse implements Responsable
{
    public function toResponse($request): RedirectResponse
    {
        return redirect()->to(
            config('filament.auth.pages.login') ? route('filament.auth.login') : config('filament.path'),
        );
    }
}
