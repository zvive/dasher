<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

use Closure;

trait CanBeHidden
{
    protected bool | Closure $isHidden  = false;
    protected bool | Closure $isVisible = true;

    public function hidden(bool | Closure $condition = true) : static
    {
        $this->isHidden = $condition;

        return $this;
    }

    public function isHidden() : bool
    {
        if (\value($this->isHidden)) {
            return true;
        }

        return ! \value($this->isVisible);
    }

    public function visible(bool | Closure $condition = true) : static
    {
        $this->isVisible = $condition;

        return $this;
    }
}
