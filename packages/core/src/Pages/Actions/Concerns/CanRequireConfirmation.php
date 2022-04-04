<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

trait CanRequireConfirmation
{
    protected bool $isConfirmationRequired = false;

    public function isConfirmationRequired() : bool
    {
        return $this->isConfirmationRequired;
    }

    public function requiresConfirmation(bool $condition = true) : static
    {
        $this->isConfirmationRequired = $condition;

        return $this;
    }
}
