<?php

namespace Dasher\Tables\Actions\Concerns;

use Dasher\Tables\Contracts\HasTable;
use Dasher\Tables\Table;

trait BelongsToTable
{
    protected Table $table;

    public function table(Table $table): static
    {
        $this->table = $table;

        return $this;
    }

    public function getTable(): Table
    {
        return $this->table;
    }

    public function getLivewire(): HasTable
    {
        return $this->getTable()->getLivewire();
    }
}
