<?php

declare(strict_types=1);

namespace Dasher\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakePageCommand extends Command
{
    use Concerns\CanManipulateFiles;
    use Concerns\CanValidateInput;
    protected $description = 'Creates a Dasher page class and view.';
    protected $signature   = 'z:dash-page {name?} {--R|resource=} {--F|force}';

    public function handle() : int
    {
        $page = (string) Str::of($this->argument('name') ?? $this->askRequired('Name (e.g. `Settings`)', 'name'))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
        $pageClass     = (string) Str::of($page)->afterLast('\\');
        $pageNamespace = Str::of($page)->contains('\\') ?
            (string) Str::of($page)->beforeLast('\\') :
            '';

        $resource      = null;
        $resourceClass = null;

        $resourceInput = $this->option('resource') ?? $this->ask('(Optional) Resource (e.g. `UserResource`)');

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

        $view = Str::of($page)
            ->prepend($resource === null ? 'filament\\pages\\' : "filament\\resources\\{$resource}\\pages\\")
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');

        $path = \app_path(
            (string) Str::of($page)
                ->prepend($resource === null ? 'Dasher\\Pages\\' : "Dasher\\Resources\\{$resource}\\Pages\\")
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
            $viewPath,
        ])) {
            return static::INVALID;
        }

        if ($resource === null) {
            $this->copyStubToApp('Page', $path, [
                'class'     => $pageClass,
                'namespace' => 'App\\Dasher\\Pages'.($pageNamespace !== '' ? "\\{$pageNamespace}" : ''),
                'view'      => $view,
            ]);
        } else {
            $this->copyStubToApp('ResourcePage', $path, [
                'baseResourcePage'      => 'Dasher\\Resources\\Pages\\Page',
                'baseResourcePageClass' => 'Page',
                'namespace'             => "App\\Dasher\\Resources\\{$resource}\\Pages".($pageNamespace !== '' ? "\\{$pageNamespace}" : ''),
                'resource'              => $resource,
                'resourceClass'         => $resourceClass,
                'resourcePageClass'     => $pageClass,
                'view'                  => $view,
            ]);
        }

        $this->copyStubToApp('PageView', $viewPath);

        $this->info("Successfully created {$page}!");

        if ($resource !== null) {
            $this->info("Make sure to register the page in `{$resourceClass}::getPages()`.");
        }

        return static::SUCCESS;
    }
}
