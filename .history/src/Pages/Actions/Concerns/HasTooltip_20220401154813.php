<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

use Closure;

trait HasTooltip
{
    protected string | Closure | null $tooltip = null;

    public function getTooltip() : ?string
    {
        return \value($this->tooltip);
    }

    public function tooltip(string | Closure | null $tooltip) : static
    {
        $this->tooltip = $tooltip;

        return $this;
    }
}
