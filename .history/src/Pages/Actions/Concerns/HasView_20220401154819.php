<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

trait HasView
{
    protected string $view;

    public function getView() : string
    {
        return $this->view;
    }

    public function view(string $view) : static
    {
        $this->view = $view;

        return $this;
    }
}
