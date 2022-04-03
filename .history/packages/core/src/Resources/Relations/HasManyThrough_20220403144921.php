<?php

declare(strict_types=1);

namespace Dasher\Resources\Relations;

use Illuminate\Database\Eloquent\Builder;

class HasManyThrough extends HasManyRelationManager
{
    // https://github.com/laravel/framework/issues/4962
    protected function getTableQuery() : Builder
    {
        $query = parent::getTableQuery();

        /** @var Builder $query */
        $query->select($query->getModel()->getTable().'.*');

        return $query;
    }
}
