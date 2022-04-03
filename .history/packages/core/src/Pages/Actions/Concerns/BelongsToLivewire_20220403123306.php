<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

use Dasher\Pages\Page;

trait BelongsToLivewire
{
    protected Page $livewire;

    public function getLivewire() : Page
    {
        return $this->livewire;
    }

    public function livewire(Page $livewire) : static
    {
        $this->livewire = $livewire;

        return $this;
    }
}
