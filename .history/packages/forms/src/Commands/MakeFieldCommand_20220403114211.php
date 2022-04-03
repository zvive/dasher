<?php

declare(strict_types=1);

namespace Dasher\Forms\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeFieldCommand extends Command
{
    use Concerns\CanManipulateFiles;
    use Concerns\CanValidateInput;
    protected array $config = [];
    protected $description  = 'Creates a form field class and view.';
    protected $signature    = 'make:form-field {name?} {--F|force}';

    protected function setup()
    {
        $field          = $this->getArgument('name', 'Name (e.g. `RangeSlider`)');
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

    protected function getNamespace()
    {
        $rootNamespace = $dashConfig['namespace'] ?? 'App';
        $rootNamespace .= '\\Forms\\Components';
        if ( ! empty($fieldNamespace)) {
            $rootNamespace .= '\\'.$fieldNamespace;
        }
    }

    public function handle() : int
    {
        $this->setup();

        // $dashboard = $this->getArgument('dashboard', 'Dashboard (e.g. `dashboard`)');
        // if ( ! empty($dashboard) && \config()->has("dasher.dashboards.{$dashboard}")) {
        //     $dashConfig = \config()->get("dasher.dashboards.{$dashboard}");
        // }

        $view = Str::of($this->config['field'])
            ->prepend('forms\\components\\')
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');
        $path = \base_path(
            (string) Str::of($this->config['field'])
                ->prepend("{$this->config['root_path']}\\Forms\\Components\\")
                ->replace('\\', '/')
                ->append('.php'),
        );
        $viewPath = \resource_path(
            (string) Str::of($view)
                ->replace('.', '/')
                ->prepend($this->config['view_path'])
                ->append('.blade.php'),
        );

        if ( ! $this->option('force') && $this->checkForCollision([
            $path,
        ])) {
            return static::INVALID;
        }

        $this->copyStubToApp('Field', $path, [
            'class'     => $this->config['field_class'],
            'namespace' => $this->config['field_namespace'], ,
            'view'      => $view,
        ]);

        if ( ! $this->fileExists($viewPath)) {
            $this->copyStubToApp('FieldView', $viewPath);
        }

        $this->info("Successfully created {$this->config['field']}!");

        return static::SUCCESS;
    }
}
