<?php

declare(strict_types=1);

namespace Admin\Pages\Actions\Concerns;

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
