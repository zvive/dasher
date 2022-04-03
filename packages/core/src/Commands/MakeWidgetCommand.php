<?php

declare(strict_types=1);

namespace Dasher\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeWidgetCommand extends Command
{
    use Concerns\CanManipulateFiles;
    use Concerns\CanValidateInput;
    protected $description = 'Creates a Dasher widget class.';
    protected $signature   = 'z:dash-widget {name?} {--R|resource=} {--C|chart} {--T|table} {--S|stats-overview} {--F|force}';

    public function handle() : int
    {
        $widget = (string) Str::of($this->argument('name') ?? $this->askRequired('Name (e.g. `BlogPostsChart`)', 'name'))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
        $widgetClass     = (string) Str::of($widget)->afterLast('\\');
        $widgetNamespace = Str::of($widget)->contains('\\') ?
            (string) Str::of($widget)->beforeLast('\\') :
            '';

        $resource      = null;
        $resourceClass = null;

        $resourceInput = $this->option('resource') ?? $this->ask('(Optional) Resource (e.g. `BlogPostResource`)');

        if ($resourceInput !== null) {
            $resource = (string) Str::of($resourceInput)
                ->studly()
                ->trim('/')
                ->trim('\\')
                ->trim(' ')
                ->replace('/', '\\');

            if ( ! Str::of($resource)->endsWith('Resource')) {
                $resource .= 'Resource';
            }

            $resourceClass = (string) Str::of($resource)
                ->afterLast('\\');
        }

        $view = Str::of($widget)
            ->prepend($resource === null ? 'dasher\\widgets\\' : "dasher\\resources\\{$resource}\\widgets\\")
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');

        $path = \app_path(
            (string) Str::of($widget)
                ->prepend($resource === null ? 'Dasher\\Widgets\\' : "Dasher\\Resources\\{$resource}\\Widgets\\")
                ->replace('\\', '/')
                ->append('.php'),
        );

        $viewPath = \resource_path(
            (string) Str::of($view)
                ->replace('.', '/')
                ->prepend('views/')
                ->append('.blade.php'),
        );

        if ( ! $this->option('force') && $this->checkForCollision([
            $path,
            ($this->option('stats-overview') || $this->option('chart')) ?: $viewPath,
        ])) {
            return static::INVALID;
        }

        if ($this->option('chart')) {
            $chart = $this->choice(
                'Chart type',
                [
                    'Bar chart',
                    'Bubble chart',
                    'Doughnut chart',
                    'Line chart',
                    'Pie chart',
                    'Polar area chart',
                    'Radar chart',
                    'Scatter chart',
                ],
            );

            $this->copyStubToApp('ChartWidget', $path, [
                'class'     => $widgetClass,
                'namespace' => \filled($resource) ? "App\\Dasher\\Resources\\{$resource}\\Widgets".($widgetNamespace !== '' ? "\\{$widgetNamespace}" : '') : 'App\\Dasher\\Widgets'.($widgetNamespace !== '' ? "\\{$widgetNamespace}" : ''),
                'chart'     => Str::studly($chart),
            ]);
        } elseif ($this->option('table')) {
            $this->copyStubToApp('TableWidget', $path, [
                'class'     => $widgetClass,
                'namespace' => \filled($resource) ? "App\\Dasher\\Resources\\{$resource}\\Widgets".($widgetNamespace !== '' ? "\\{$widgetNamespace}" : '') : 'App\\Dasher\\Widgets'.($widgetNamespace !== '' ? "\\{$widgetNamespace}" : ''),
            ]);
        } elseif ($this->option('stats-overview')) {
            $this->copyStubToApp('StatsOverviewWidget', $path, [
                'class'     => $widgetClass,
                'namespace' => \filled($resource) ? "App\\Dasher\\Resources\\{$resource}\\Widgets".($widgetNamespace !== '' ? "\\{$widgetNamespace}" : '') : 'App\\Dasher\\Widgets'.($widgetNamespace !== '' ? "\\{$widgetNamespace}" : ''),
            ]);
        } else {
            $this->copyStubToApp('Widget', $path, [
                'class'     => $widgetClass,
                'namespace' => \filled($resource) ? "App\\Dasher\\Resources\\{$resource}\\Widgets".($widgetNamespace !== '' ? "\\{$widgetNamespace}" : '') : 'App\\Dasher\\Widgets'.($widgetNamespace !== '' ? "\\{$widgetNamespace}" : ''),
                'view'      => $view,
            ]);

            $this->copyStubToApp('WidgetView', $viewPath);
        }

        $this->info("Successfully created {$widget}!");

        if ($resource !== null) {
            $this->info("Make sure to register the widget in `{$resourceClass}::getWidgets()`, and then again in `getHeaderWidgets()` or `getFooterWidgets()` of any `{$resourceClass}` page.");
        }

        return static::SUCCESS;
    }
}
