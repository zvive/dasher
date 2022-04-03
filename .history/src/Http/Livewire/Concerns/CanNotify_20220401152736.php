<?php

namespace Dasher\Http\Livewire\Concerns;

use Dasher\Facades\Dasher;

trait CanNotify
{
    public function notify(string $status, string $message, bool $isAfterRedirect = false): void
    {
        Filament::notify($status, $message, $isAfterRedirect);
    }
}
