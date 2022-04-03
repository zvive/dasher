<?php

declare(strict_types=1);

namespace Admin\Navigation;

use Illuminate\Support\Traits\Conditionable;

class NavigationBuilder
{
    use Conditionable;

    /** @var array<string, array<\Filament\Navigation\NavigationItem>> */
    protected array $groups = [];

    /** @var array<\Filament\Navigation\NavigationItem> */
    protected array $items = [];

    public function getGroups() : array
    {
        return $this->groups;
    }

    public function getItems() : array
    {
        return $this->items;
    }

    public function group(string $name, array $items = []) : static
    {
        $this->groups[$name] = \collect($items)->map(
            fn (NavigationItem $item, int $index) => $item->group($name)->sort($index),
        )->toArray();

        return $this;
    }

    public function item(NavigationItem $item) : static
    {
        $this->items[] = $item;

        return $this;
    }

    /** @param array<\Filament\Navigation\NavigationItem> $items */
    public function items(array $items) : static
    {
        $this->items = \array_merge($this->items, $items);

        return $this;
    }
}
