<?php

declare(strict_types=1);

namespace Dasher;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

abstract class PluginServiceProvider extends PackageServiceProvider
{
    public static string $name;
    protected array $beforeCoreScripts = [];
    protected array $pages             = [];
    protected array $relationships     = [];
    protected array $resources         = [];
    protected array $scripts           = [];
    protected array $styles            = [];
    protected array $widgets           = [];

    public function configurePackage(Package $package) : void
    {
        $this->packageConfiguring($package);

        $package
            ->name(static::$name)
            ->hasCommands($this->getCommands());

        $configFileName = $package->shortName();

        if (\file_exists($this->package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (\file_exists($this->package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (\file_exists($this->package->basePath('/../resources/views'))) {
            $package->hasViews();
        }

        $this->packageConfigured($package);
    }

    protected function getBeforeCoreScripts() : array
    {
        return $this->beforeCoreScripts;
    }

    protected function getCommands() : array
    {
        return [];
    }

    protected function getPages() : array
    {
        return $this->pages;
    }

    protected function getRelationships() : array
    {
        return $this->relationships;
    }

    protected function getResources() : array
    {
        return $this->resources;
    }

    protected function getScriptData() : array
    {
        return [];
    }

    protected function getScripts() : array
    {
        return $this->scripts;
    }

    protected function getStyles() : array
    {
        return $this->styles;
    }

    protected function getUserMenuItems() : array
    {
        return [];
    }

    protected function getWidgets() : array
    {
        return $this->widgets;
    }

    public function packageBooted() : void
    {
        foreach ($this->getPages() as $page) {
            Livewire::component($page::getName(), $page);
        }

        foreach ($this->getRelationships() as $manager) {
            Livewire::component($manager::getName(), $manager);
        }

        foreach ($this->getResources() as $resource) {
            foreach ($resource::getPages() as $page) {
                Livewire::component($page['class']::getName(), $page['class']);
            }

            foreach ($resource::getRelationships() as $relation) {
                Livewire::component($relation::getName(), $relation);
            }

            foreach ($resource::getWidgets() as $widget) {
                Livewire::component($widget::getName(), $widget);
            }
        }

        foreach ($this->getWidgets() as $widget) {
            Livewire::component($widget::getName(), $widget);
        }

        $this->registerMacros();
    }

    public function packageConfigured(Package $package) : void
    {
    }

    public function packageConfiguring(Package $package) : void
    {
    }

    public function packageRegistered() : void
    {
        $this->app->singletonIf(
            'dasher',
            fn () : DasherService => \app(DasherService::class),
        );

        Facades\Dasher::registerPages($this->getPages());
        Facades\Dasher::registerResources($this->getResources());
        Facades\Dasher::registerUserMenuItems($this->getUserMenuItems());
        Facades\Dasher::registerWidgets($this->getWidgets());

        Facades\Dasher::serving(function () {
            Facades\Dasher::registerScripts($this->getBeforeCoreScripts(), true);
            Facades\Dasher::registerScripts($this->getScripts());
            Facades\Dasher::registerStyles($this->getStyles());
            Facades\Dasher::registerScriptData($this->getScriptData());
        });
    }

    protected function registerMacros() : void
    {
    }
}
