<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

trait HasName
{
    protected string $name;

    public function getName() : string
    {
        return $this->name;
    }

    public function name(string $name) : static
    {
        $this->name = $name;

        return $this;
    }
}
