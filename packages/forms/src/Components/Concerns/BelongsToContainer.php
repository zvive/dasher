<?php

declare(strict_types=1);

namespace Dasher\Forms\Components\Concerns;

use Dasher\Forms\ComponentContainer;
use Dasher\Forms\Contracts\HasForms;

trait BelongsToContainer
{
    protected ComponentContainer $container;

    public function container(ComponentContainer $container) : static
    {
        $this->container = $container;

        return $this;
    }

    public function getContainer() : ComponentContainer
    {
        return $this->container;
    }

    public function getLivewire() : HasForms
    {
        return $this->getContainer()->getLivewire();
    }
}
