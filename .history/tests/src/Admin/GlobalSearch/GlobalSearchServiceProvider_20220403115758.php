<?php

namespace Dasher\Tests\Admin\GlobalSearch;

use Dasher\PluginServiceProvider;
use Dasher\Tests\Admin\Fixtures\Resources\PostResource;

class GlobalSearchServiceProvider extends PluginServiceProvider
{
    public static string $name = 'global-search';

    protected function getResources(): array
    {
        return [
            PostResource::class,
        ];
    }
}
