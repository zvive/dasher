<?php

declare(strict_types=1);

namespace Dasher\GlobalSearch;

class GlobalSearchResult
{
    public function __construct(
        public string $title,
        public string $url,
        public array $details = [],
    ) {}
}
