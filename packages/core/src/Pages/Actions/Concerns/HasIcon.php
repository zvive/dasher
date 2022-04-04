<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

trait HasIcon
{
    protected ?string $icon = null;

    public function getIcon() : ?string
    {
        return $this->icon;
    }

    public function icon(string $icon) : static
    {
        $this->icon = $icon;

        return $this;
    }
}
