<?php

declare(strict_types=1);

namespace Admin\Pages\Actions\Concerns;

use Closure;
use Filament\Forms\ComponentContainer;

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
