<?php

namespace Dasher\Widgets;

use Closure;
use Dasher\Tables;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TableWidget extends Widget implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $view = 'dasher::widgets.table-widget';

    protected static ?string $heading = null;

    protected function getTableHeading(): string | Closure | null
    {
        return static::$heading ?? (string) Str::of(class_basename(static::class))
            ->beforeLast('Widget')
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate($this->getTableRecordsPerPage());
    }
}
