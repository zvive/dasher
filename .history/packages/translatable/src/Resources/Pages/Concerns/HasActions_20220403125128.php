<?php

namespace Dasher\Resources\Pages\Concerns;

declare(strict_types=1);

trait HasActions
{
    protected function getActions() : array
    {
        return \array_merge(
            [$this->getActiveFormLocaleSelectAction()],
            parent::getActions() ?? [],
        );
    }
}
