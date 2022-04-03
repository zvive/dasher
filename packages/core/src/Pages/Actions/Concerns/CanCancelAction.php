<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

trait CanCancelAction
{
    protected bool $canCancelAction = false;

    public function canCancelAction() : bool
    {
        return $this->canCancelAction;
    }

    public function cancel(bool $condition = true) : static
    {
        $this->canCancelAction = $condition;

        return $this;
    }
}
