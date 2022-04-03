<?php

namespace Dasher\Forms\Components\Concerns;

use Dasher\Forms\Components\Component;
use Dasher\Forms\Components\Contracts\CanConcealComponents;

trait CanBeConcealed
{
    public function getConcealingComponent(): ?Component
    {
        $parentComponent = $this->getContainer()->getParentComponent();

        if (! $parentComponent) {
            return null;
        }

        if (! $parentComponent instanceof CanConcealComponents) {
            return $parentComponent->getConcealingComponent();
        }

        return $parentComponent;
    }
}
