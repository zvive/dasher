<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

trait CanBeOutlined
{
    protected bool $isOutlined = false;

    public function isOutlined() : bool
    {
        return $this->isOutlined;
    }

    public function outlined(bool $condition = true) : static
    {
        $this->isOutlined = $condition;

        return $this;
    }
}
