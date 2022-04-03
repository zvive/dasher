<?php

declare(strict_types=1);

namespace Dasher\GlobalSearch;

use Dasher\Facades\Dasher;

class DefaultGlobalSearchProvider implements Contracts\GlobalSearchProvider
{
    public function getResults(string $query) : ?GlobalSearchResults
    {
        $builder = GlobalSearchResults::make();

        foreach (Dasher::getResources() as $resource) {
            if ( ! $resource::canGloballySearch()) {
                continue;
            }

            $resourceResults = $resource::getGlobalSearchResults($query);

            if ( ! $resourceResults->count()) {
                continue;
            }

            $builder->category($resource::getPluralLabel(), $resourceResults);
        }

        return $builder;
    }
}
