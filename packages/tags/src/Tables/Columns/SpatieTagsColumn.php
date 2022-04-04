<?php

declare(strict_types=1);

namespace Dasher\Tables\Columns;

use Illuminate\Database\Eloquent\Builder;

class SpatieTagsColumn extends TagsColumn
{
    protected ?string $type = null;

    public function applyEagerLoading(Builder $query) : Builder
    {
        if ($this->isHidden()) {
            return $query;
        }

        return $query->with(['tags']);
    }

    public function getTags() : array
    {
        $record = $this->getRecord();

        if ( ! \method_exists($record, 'tagsWithType')) {
            return [];
        }

        $type = $this->getType();
        $tags = $record->tagsWithType($type);

        return $tags->pluck('name')->toArray();
    }

    public function getType() : ?string
    {
        return $this->type;
    }

    public function type(?string $type) : static
    {
        $this->type = $type;

        return $this;
    }
}
