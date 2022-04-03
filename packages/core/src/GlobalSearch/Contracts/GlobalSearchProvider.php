<?php

declare(strict_types=1);

namespace Dasher\GlobalSearch\Contracts;

use Dasher\GlobalSearch\GlobalSearchResults;

interface GlobalSearchProvider
{
    public function getResults(string $query) : ?GlobalSearchResults;
}
