<?php

namespace Dasher\Tests\Admin\Resources;

use Dasher\PluginServiceProvider;
use Dasher\Tests\Admin\Fixtures\Resources\PostResource;

class ResourcesServiceProvider extends PluginServiceProvider
{
    public static string $name = 'resources';

    protected function getResources(): array
    {
        return [
            PostResource::class,
        ];
    }
}
