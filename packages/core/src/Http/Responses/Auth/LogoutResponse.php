<?php

declare(strict_types=1);

namespace Dasher\Http\Responses\Auth;

use Illuminate\Http\RedirectResponse;
use Dasher\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;

class LogoutResponse implements Responsable
{
    public function toResponse($request) : RedirectResponse
    {
        return \redirect()->to(
            \config('dasher.auth.pages.login') ? \route('dasher.auth.login') : \config('dasher.path'),
        );
    }
}
