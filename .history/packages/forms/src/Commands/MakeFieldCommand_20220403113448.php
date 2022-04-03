<?php

declare(strict_types=1);

namespace Dasher\Forms\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeFieldCommand extends Command
{
    use Concerns\CanManipulateFiles;
    use Concerns\CanValidateInput;
    protected $description = 'Creates a form field class and view.';
    protected $signature   = 'make:form-field {name?} {--F|force}';

    protected array $config = [];

    public function getArgument($field, $question) : string
    {
        return (string) Str::of($this->argument($field) ?? $this->askRequired($question, $field))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
    }

    protected function setup(){
        $field     = $this->getArgument('name', 'Name (e.g. `RangeSlider`)');
        $dashboard = $this->getArgument('dashboard', 'Dashboard (e.g. `dashboard`)');
        $config = !empty($dashboard) ? \config()->get("dasher.dashboards.{$dashboard}") : [];
        $rootPath = $dashConfig['root_path'] ?? 'App';
        $rootViewPath = "views/{$config['view_path']}/" ?? 'views/';
        $this->config = [
            'dashboard' => $dashboard ?? '',
            'namespace' => $config['namespace'] ?? 'App',
            'root_path' => $config['root_path'] ?? 'app',
            'root_view_path' => $rootViewPath,
            'field' => $field,
            'field_class' => (string) Str::of($field)->afterLast('\\'),
            'field_namespace' => Str::of($field)->contains('\\') ?
            (string) Str::of($field)->beforeLast('\\') :
            '',
            'component_path' =>
        ];

    }

    public function handle() : int
    {
        $this->setup();

        // $dashboard = $this->getArgument('dashboard', 'Dashboard (e.g. `dashboard`)');
        // if ( ! empty($dashboard) && \config()->has("dasher.dashboards.{$dashboard}")) {
        //     $dashConfig = \config()->get("dasher.dashboards.{$dashboard}");
        // }
        $fieldClass     = (string) Str::of($field)->afterLast('\\');
        $fieldNamespace = Str::of($field)->contains('\\') ?
            (string) Str::of($field)->beforeLast('\\') :
            '';

        $view = Str::of($field)
            ->prepend('forms\\components\\')
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');
        $path     = \base_path(
            (string) Str::of($field)
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
            'class'     => $fieldClass,
            'namespace' => $rootNamespace.($fieldNamespace !== '' ? "\\{$fieldNamespace}" : ''),
            'view'      => $view,
        ]);

        if ( ! $this->fileExists($viewPath)) {
            $this->copyStubToApp('FieldView', $viewPath);
        }

        $this->info("Successfully created {$field}!");

        return static::SUCCESS;
    }

    protected function getNamespace(){
        $rootNamespace = $dashConfig['namespace'] ?? 'App';
        $rootNamespace .= '\\Forms\\Components';
        if(!empty($fieldNamespace)){
            $rootNamespace .= '\\'.$fieldNamespace;
        }
    }
}
