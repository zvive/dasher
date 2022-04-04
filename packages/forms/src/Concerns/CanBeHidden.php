<?php

namespace Dasher\Forms\Concerns;

trait CanBeHidden
{
    public function isHidden(): bool
    {
        return (bool) $this->getParentComponent()?->isHidden();
    }
}
