<?php

namespace Dasher\GlobalSearch\Contracts;

use Dasher\GlobalSearch\GlobalSearchResults;

interface GlobalSearchProvider
{
    public function getResults(string $query): ?GlobalSearchResults;
}
