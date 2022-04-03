<?php

declare(strict_types=1);

namespace Dasher\Forms\Components;

use Closure;
use Spatie\Tags\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SpatieTagsInput extends TagsInput
{
    protected string | Closure | null $type = null;

    protected function setUp() : void
    {
        parent::setUp();

        $this->afterStateHydrated(function (self $component, ?Model $record) : void {
            if ( ! $record) {
                $component->state([]);

                return;
            }

            if ( ! \method_exists($record, 'tagsWithType')) {
                return;
            }

            $type = $component->getType();
            $tags = $record->tagsWithType($type);

            $component->state($tags->pluck('name'));
        });

        $this->saveRelationshipsUsing(function (self $component, ?Model $record, array $state) {
            if ( ! (\method_exists($record, 'syncTagsWithType') && \method_exists($record, 'syncTags'))) {
                return;
            }

            if ($type = $component->getType()) {
                $record->syncTagsWithType($state, $type);

                return;
            }

            $record->syncTags($state);
        });

        $this->dehydrated(false);
    }

    public function getSuggestions() : array
    {
        if ($this->suggestions !== null) {
            return parent::getSuggestions();
        }

        $type = $this->getType();

        return Tag::query()
            ->when(
                \filled($type),
                fn (Builder $query) => $query->where('type', $type),
                fn (Builder $query) => $query->where('type', null),
            )
            ->pluck('name')
            ->toArray();
    }

    public function getType() : ?string
    {
        return $this->evaluate($this->type);
    }

    public function type(string | Closure | null $type) : static
    {
        $this->type = $type;

        return $this;
    }
}
