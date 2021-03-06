<?php

declare(strict_types=1);

namespace Dasher;

use Illuminate\Support\ServiceProvider;

class SettingsPluginServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->getCommands());

            $this->publishes([
                __DIR__.'/../resources/views' => \resource_path('views/vendor/dasher-settings-plugin'),
            ]);
        }

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'dasher-settings-plugin');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dasher-settings-plugin');
    }

    protected function getCommands() : array
    {
        $commands = [
            Commands\MakeSettingsPageCommand::class,
        ];

        $aliases = [];

        foreach ($commands as $command) {
            $class = 'Dasher\\Commands\\Aliases\\'.\class_basename($command);

            if ( ! \class_exists($class)) {
                continue;
            }

            $aliases[] = $class;
        }

        return \array_merge($commands, $aliases);
    }
}
