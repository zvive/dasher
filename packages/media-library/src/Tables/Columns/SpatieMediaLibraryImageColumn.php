<?php

namespace Dasher\Tables\Columns;

use Illuminate\Database\Eloquent\Builder;

class SpatieMediaLibraryImageColumn extends ImageColumn
{
    protected ?string $collection = null;

    protected string $conversion = '';

    public function collection(string $collection): static
    {
        $this->collection = $collection;

        return $this;
    }

    public function conversion(string $conversion): static
    {
        $this->conversion = $conversion;

        return $this;
    }

    public function getCollection(): ?string
    {
        return $this->collection ?? 'default';
    }

    public function getConversion(): string
    {
        return $this->conversion ?? '';
    }

    public function getImagePath(): ?string
    {
        $state = $this->getState();

        if ($state) {
            return $state;
        }

        $record = $this->getRecord();

        if (! method_exists($record, 'getFirstMediaUrl')) {
            return $state;
        }

        return $record->getFirstMediaUrl($this->getCollection(), $this->getConversion());
    }

    public function applyEagerLoading(Builder $query): Builder
    {
        if ($this->isHidden()) {
            return $query;
        }

        return $query->with(['media']);
    }
}
