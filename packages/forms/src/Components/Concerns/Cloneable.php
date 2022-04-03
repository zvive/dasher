<?php

namespace Dasher\Forms\Components\Concerns;

trait Cloneable
{
    public function getClone(): static
    {
        return clone $this;
    }
}
