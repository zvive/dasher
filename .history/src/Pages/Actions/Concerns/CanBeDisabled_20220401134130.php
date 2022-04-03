<?php

declare(strict_types=1);

namespace Admin\Pages\Actions\Concerns;

use Closure;

trait CanBeDisabled
{
    protected bool | Closure $isDisabled = false;

    public function disabled(bool | Closure $condition = true) : static
    {
        $this->isDisabled = $condition;

        return $this;
    }

    public function isDisabled() : bool
    {
        return \value($this->isDisabled);
    }

    public function isEnabled() : bool
    {
        return ! $this->isDisabled();
    }
}
