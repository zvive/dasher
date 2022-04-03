<?php

declare(strict_types=1);

namespace Dasher\Http\Livewire\Concerns;

use Dasher\Facades\Dasher;

trait CanNotify
{
    public function notify(string $status, string $message, bool $isAfterRedirect = false) : void
    {
        Dasher::notify($status, $message, $isAfterRedirect);
    }
}
