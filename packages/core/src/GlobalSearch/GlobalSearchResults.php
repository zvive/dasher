<?php

declare(strict_types=1);

namespace Dasher\GlobalSearch;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;

class GlobalSearchResults
{
    protected Collection $categories;

    final public function __construct()
    {
        $this->categories = Collection::make();
    }

    public function category(string $name, array | Arrayable $results = []) : static
    {
        $this->categories[$name] = $results;

        return $this;
    }

    public function getCategories() : Collection
    {
        return $this->categories;
    }

    public static function make() : static
    {
        return new static();
    }
}
