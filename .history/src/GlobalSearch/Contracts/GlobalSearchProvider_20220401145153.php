<?php

namespace Dasher\GlobalSearch\Contracts;

use Filament\GlobalSearch\GlobalSearchResults;

interface GlobalSearchProvider
{
    public function getResults(string $query): ?GlobalSearchResults;
}
