<?php

declare(strict_types=1);

namespace Dasher\Forms\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeLayoutComponentCommand extends Command
{
    use Concerns\CanManipulateFiles;
    use Concerns\CanValidateInput;
    protected array $config = [];
    protected $description  = 'Creates a form layout component class and view.';
    protected $signature    = 'make:form-layout {name?} {--F|force}';

    public function setup()
    {
        $component      = $this->getArgument('name', 'Name (e.g. `Wizard`)');
        $dashboard      = $this->getArgument('dashboard', 'Dashboard (e.g. `dashboard`)');
        $config         = ! empty($dashboard) ? \config()->get("dasher.dashboards.{$dashboard}") : [];
        $fieldNamespace = Str::of($field)->contains('\\') ?
        (string) Str::of($field)->beforeLast('\\') :
        '';
        $rootPath      = $dashConfig['root_path']        ?? 'App';
        $viewPath      = "views/{$config['view_path']}/" ?? 'views/';
        $namespace     = $config['namespace']            ?? 'App';
        $rootNamespace = $namespace.'Forms\\Components';
        $rootNamespace = $rootNamespace.($fieldNamespace !== '' ? '\\'.$fieldNamespace : '');
        $this->config  = [
            'dashboard'       => $dashboard ?? '',
            'namespace'       => $config['namespace'] ?? 'App',
            'root_path'       => $config['root_path'] ?? 'app',
            'view_path'       => $viewPath,
            'field'           => $field,
            'field_class'     => (string) Str::of($field)->afterLast('\\'),
            'field_namespace' => $fieldNamespace,
        ];
    }

    public function getArgument($field, $question) : string
    {
        return (string) Str::of($this->argument($field) ?? $this->askRequired($question, $field))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
    }

    public function handle() : int
    {
        $component = (string) Str::of($this->argument('name') ?? $this->askRequired('Name (e.g. `Wizard`)', 'name'))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
        $componentClass     = (string) Str::of($component)->afterLast('\\');
        $componentNamespace = Str::of($component)->contains('\\') ?
            (string) Str::of($component)->beforeLast('\\') :
            '';

        $view = Str::of($component)
            ->prepend('forms\\components\\')
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');

        $path = \app_path(
            (string) Str::of($component)
                ->prepend('Forms\\Components\\')
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
        ])) {
            return static::INVALID;
        }

        $this->copyStubToApp('LayoutComponent', $path, [
            'class'     => $componentClass,
            'namespace' => 'App\\Forms\\Components'.($componentNamespace !== '' ? "\\{$componentNamespace}" : ''),
            'view'      => $view,
        ]);

        if ( ! $this->fileExists($viewPath)) {
            $this->copyStubToApp('LayoutComponentView', $viewPath);
        }

        $this->info("Successfully created {$component}!");

        return static::SUCCESS;
    }
}
