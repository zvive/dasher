<?php

declare(strict_types=1);

namespace Dasher;

use ReflectionClass;
use Dasher\Pages\Page;
use Livewire\Livewire;
use Livewire\Component;
use Dasher\Facades\Dasher;
use Dasher\Widgets\Widget;
use Dasher\Pages\Dashboard;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Dasher\Resources\Resource;
use Dasher\Widgets\AccountWidget;
use Dasher\Http\Livewire\Auth\Login;
use Illuminate\Filesystem\Filesystem;
use Dasher\Http\Livewire\GlobalSearch;
use Dasher\Widgets\DashboardInfoWidget;
use Spatie\LaravelPackageTools\Package;
use Symfony\Component\Finder\SplFileInfo;
use Dasher\Http\Responses\Auth\LoginResponse;
use Dasher\Http\Responses\Auth\LogoutResponse;
use Dasher\Http\Middleware\MirrorConfigToSubpackages;
use Dasher\Http\Middleware\DispatchServingDasherEvent;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Dasher\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Dasher\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;

class DasherServiceProvider extends PackageServiceProvider
{
    protected function bootLivewireComponents() : void
    {
        Livewire::addPersistentMiddleware([
            DispatchServingDasherEvent::class,
            MirrorConfigToSubpackages::class,
        ]);

        Livewire::listen('component.hydrate', function ($component) {
            $this->app->singleton(Component::class, fn () => $component);
        });

        Livewire::component('dasher.core.auth.login', Login::class);
        Livewire::component('dasher.core.global-search', GlobalSearch::class);
        Livewire::component('dasher.core.pages.dashboard', Dashboard::class);
        Livewire::component('dasher.core.widgets.account-widget', AccountWidget::class);
        Livewire::component('dasher.core.widgets.dashboard-info-widget', DashboardInfoWidget::class);

        $this->registerLivewireComponentDirectory(\config('dasher.livewire.path'), \config('dasher.livewire.namespace'), 'dasher.');
    }

    public function configurePackage(Package $package) : void
    {
        $package
            ->name('dasher')
            ->hasCommands($this->getCommands())
            ->hasConfigFile()
            ->hasRoutes(['web'])
            ->hasTranslations()
            ->hasViews();
    }

    protected function discoverPages() : void
    {
        $filesystem = \app(Filesystem::class);

        Dasher::registerPages(\config('dasher.pages.register', []));

        if ( ! $filesystem->exists(\config('dasher.pages.path'))) {
            return;
        }

        Dasher::registerPages(\collect($filesystem->allFiles(\config('dasher.pages.path')))
            ->map(function (SplFileInfo $file) : string {
                return (string) Str::of(\config('dasher.pages.namespace'))
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', '']);
            })
            ->filter(fn (string $class) : bool => \is_subclass_of($class, Page::class) && ( ! (new ReflectionClass($class))->isAbstract()))
            ->toArray());
    }

    protected function discoverResources() : void
    {
        $filesystem = \app(Filesystem::class);
        $dashboards = \config('dasher.dashboards');
        foreach ($dashboards as $dash) {
            Dasher::registerResources($dash['resources'] ?? []);
        }

        if ( ! $filesystem->exists(\config('dasher.resources.path'))) {
            return;
        }

        Dasher::registerResources(\collect($filesystem->allFiles(\config('dasher.resources.path')))
            ->map(function (SplFileInfo $file) : string {
                return (string) Str::of(\config('dasher.resources.namespace'))
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', '']);
            })
            ->filter(fn (string $class) : bool => \is_subclass_of($class, Resource::class) && ( ! (new ReflectionClass($class))->isAbstract()))
            ->toArray());
    }

    protected function discoverWidgets() : void
    {
        $filesystem = \app(Filesystem::class);

        Dasher::registerWidgets(\config('dasher.widgets.register', []));

        if ( ! $filesystem->exists(\config('dasher.widgets.path'))) {
            return;
        }

        Dasher::registerWidgets(\collect($filesystem->allFiles(\config('dasher.widgets.path')))
            ->map(function (SplFileInfo $file) : string {
                return (string) Str::of(\config('dasher.widgets.namespace'))
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', '']);
            })
            ->filter(fn ($class) : bool => \is_subclass_of($class, Widget::class) && ( ! (new ReflectionClass($class))->isAbstract()))
            ->toArray());
    }

    protected function getCommands() : array
    {
        $commands = [
            Commands\MakeBelongsToManyCommand::class,
            Commands\MakeHasManyCommand::class,
            Commands\MakeHasManyThroughCommand::class,
            Commands\MakeMorphManyCommand::class,
            Commands\MakeMorphToManyCommand::class,
            Commands\MakePageCommand::class,
            Commands\MakeResourceCommand::class,
            Commands\MakeUserCommand::class,
            Commands\MakeWidgetCommand::class,
            Commands\UpgradeCommand::class,
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

    protected function mergeConfig(array $original, array $merging) : array
    {
        $array = \array_merge($original, $merging);

        foreach ($original as $key => $value) {
            if ( ! \is_array($value)) {
                continue;
            }

            if ( ! Arr::exists($merging, $key)) {
                continue;
            }

            if (\is_numeric($key)) {
                continue;
            }

            if ($key === 'middleware' || $key === 'register') {
                continue;
            }

            $array[$key] = $this->mergeConfig($value, $merging[$key]);
        }

        return $array;
    }

    protected function mergeConfigFrom($path, $key) : void
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, $this->mergeConfig(require $path, $config));
    }

    public function packageBooted() : void
    {
        $this->bootLivewireComponents();
    }

    public function packageRegistered() : void
    {
        $this->app->resolving('dasher', function () : void {
            $this->discoverPages();
            $this->discoverResources();
            $this->discoverWidgets();
        });

        $this->app->scoped('dasher', fn () : DasherService => \app(DasherService::class));

        $this->app->bind(LoginResponseContract::class, LoginResponse::class);
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);

        $this->mergeConfigFrom(__DIR__.'/../config/dasher.php', 'dasher');
    }

    protected function registerLivewireComponentDirectory(string $directory, string $namespace, string $aliasPrefix = '') : void
    {
        $filesystem = \app(Filesystem::class);

        if ( ! $filesystem->isDirectory($directory)) {
            return;
        }

        \collect($filesystem->allFiles($directory))
            ->map(function (SplFileInfo $file) use ($namespace) : string {
                return (string) Str::of($namespace)
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', '']);
            })
            ->filter(fn (string $class) : bool => \is_subclass_of($class, Component::class) && ( ! (new ReflectionClass($class))->isAbstract()))
            ->each(function (string $class) use ($namespace, $aliasPrefix) : void {
                $alias = Str::of($class)
                    ->after($namespace.'\\')
                    ->replace(['/', '\\'], '.')
                    ->prepend($aliasPrefix)
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.');

                Livewire::component($alias, $class);
            });
    }
}
