<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

trait HasId
{
    protected ?string $id = null;

    public function getId() : string
    {
        return $this->id ?? $this->getName();
    }

    public function id(string $id) : static
    {
        $this->id = $id;

        return $this;
    }
}
