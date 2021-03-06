<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

use Closure;
use Dasher\Forms\ComponentContainer;

trait CanBeMounted
{
    protected ?Closure $mountUsing = null;

    public function getMountUsing() : Closure
    {
        return $this->mountUsing ?? function ($action, ?ComponentContainer $form = null) : void {
            if ( ! $action->shouldOpenModal()) {
                return;
            }

            if ( ! $form) {
                return;
            }

            $form->fill();
        };
    }

    public function mountUsing(?Closure $callback) : static
    {
        $this->mountUsing = $callback;

        return $this;
    }
}
