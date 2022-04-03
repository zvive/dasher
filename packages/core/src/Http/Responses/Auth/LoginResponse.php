<?php

declare(strict_types=1);

namespace Dasher\Http\Responses\Auth;

use Livewire\Redirector;
use Dasher\Facades\Dasher;
use Illuminate\Http\RedirectResponse;
use Dasher\Http\Responses\Auth\Contracts\LoginResponse as Responsable;

class LoginResponse implements Responsable
{
    public function toResponse($request) : RedirectResponse | Redirector
    {
        return \redirect()->intended(Dasher::getUrl());
    }
}
