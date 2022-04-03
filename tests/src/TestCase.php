<?php

declare(strict_types=1);

namespace Dasher\Tests;

use Dasher\Tests\Models\User;
use Dasher\DasherServiceProvider;
use Livewire\LivewireServiceProvider;
use Dasher\Forms\FormsServiceProvider;
use Dasher\Tables\TablesServiceProvider;
use Dasher\SettingsPluginServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Dasher\TranslatablePluginServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    protected function defineDatabaseMigrations() : void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function getEnvironmentSetUp($app) : void
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('view.paths', \array_merge(
            $app['config']->get('view.paths'),
            [__DIR__.'/../resources/views'],
        ));
    }

    protected function getPackageProviders($app) : array
    {
        return [
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            DasherServiceProvider::class,
            FormsServiceProvider::class,
            LivewireServiceProvider::class,
            SettingsPluginServiceProvider::class,
            TranslatablePluginServiceProvider::class,
            TablesServiceProvider::class,
        ];
    }
}
