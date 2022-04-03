<?php

namespace Dasher\Tests\Admin\Pages;

use Dasher\PluginServiceProvider;
use Dasher\Tests\Admin\Fixtures\Pages\Settings;

class PagesServiceProvider extends PluginServiceProvider
{
    public static string $name = 'pages';

    protected function getPages(): array
    {
        return [
            Settings::class,
        ];
    }
}
