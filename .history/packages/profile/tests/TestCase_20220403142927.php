<?php

declare(strict_types=1);

namespace RyanChandler\FilamentProfile\Tests;

use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestCase extends Orchestra
{
    protected function setUp() : void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Dasher\\Profile\\Database\\Factories\\'.\class_basename($modelName).'Factory'
        );
    }

    public function getEnvironmentSetUp($app)
    {
        \config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_filament-profile_table.php.stub';
        $migration->up();
        */
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentProfileServiceProvider::class,
        ];
    }
}
