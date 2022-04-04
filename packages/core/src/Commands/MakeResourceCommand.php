<?php

declare(strict_types=1);

namespace Dasher\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeResourceCommand extends Command
{
    use Concerns\CanGenerateResources;
    use Concerns\CanManipulateFiles;
    use Concerns\CanValidateInput;
    protected $description = 'Creates a Dasher resource class and default page classes.';
    protected $signature   = 'z:dash-resource {name?} {--G|generate} {--S|simple} {--F|force}';

    public function handle() : int
    {
        $model = (string) Str::of($this->argument('name') ?? $this->askRequired('Model (e.g. `BlogPost`)', 'name'))
            ->studly()
            ->beforeLast('Resource')
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->studly()
            ->replace('/', '\\');
        $modelClass     = (string) Str::of($model)->afterLast('\\');
        $modelNamespace = Str::of($model)->contains('\\') ?
            (string) Str::of($model)->beforeLast('\\') :
            '';
        $pluralModelClass = (string) Str::of($modelClass)->pluralStudly();

        $resource                = "{$model}Resource";
        $resourceClass           = "{$modelClass}Resource";
        $resourceNamespace       = $modelNamespace;
        $listResourcePageClass   = "List{$pluralModelClass}";
        $manageResourcePageClass = "Manage{$pluralModelClass}";
        $createResourcePageClass = "Create{$modelClass}";
        $editResourcePageClass   = "Edit{$modelClass}";

        $baseResourcePath = \app_path(
            (string) Str::of($resource)
                ->prepend('Dasher\\Resources\\')
                ->replace('\\', '/'),
        );
        $resourcePath           = "{$baseResourcePath}.php";
        $resourcePagesDirectory = "{$baseResourcePath}/Pages";
        $listResourcePagePath   = "{$resourcePagesDirectory}/{$listResourcePageClass}.php";
        $manageResourcePagePath = "{$resourcePagesDirectory}/{$manageResourcePageClass}.php";
        $createResourcePagePath = "{$resourcePagesDirectory}/{$createResourcePageClass}.php";
        $editResourcePagePath   = "{$resourcePagesDirectory}/{$editResourcePageClass}.php";

        if ( ! $this->option('force') && $this->checkForCollision([
            $resourcePath,
            $listResourcePagePath,
            $manageResourcePagePath,
            $createResourcePagePath,
            $editResourcePagePath,
        ])) {
            return static::INVALID;
        }

        $this->copyStubToApp($this->option('simple') ? 'SimpleResource' : 'Resource', $resourcePath, [
            'createResourcePageClass' => $createResourcePageClass,
            'editResourcePageClass'   => $editResourcePageClass,
            'formSchema'              => $this->option('generate') ? $this->getResourceFormSchema(
                ($modelNamespace !== '' ? $modelNamespace : 'App\Models').'\\'.$modelClass
            ) : $this->indentString('//'),
            'indexResourcePageClass' => $this->option('simple') ? $manageResourcePageClass : $listResourcePageClass,
            'model'                  => $model,
            'modelClass'             => $modelClass,
            'namespace'              => 'App\\Dasher\\Resources'.($resourceNamespace !== '' ? "\\{$resourceNamespace}" : ''),
            'resource'               => $resource,
            'resourceClass'          => $resourceClass,
            'tableColumns'           => $this->option('generate') ? $this->getResourceTableColumns(
                ($modelNamespace !== '' ? $modelNamespace : 'App\Models').'\\'.$modelClass
            ) : $this->indentString('//'),
        ]);

        if ($this->option('simple')) {
            $this->copyStubToApp('DefaultResourcePage', $manageResourcePagePath, [
                'baseResourcePage'      => 'Dasher\\Resources\\Pages\\ManageRecords',
                'baseResourcePageClass' => 'ManageRecords',
                'namespace'             => "App\\Dasher\\Resources\\{$resource}\\Pages",
                'resource'              => $resource,
                'resourceClass'         => $resourceClass,
                'resourcePageClass'     => $manageResourcePageClass,
            ]);
        } else {
            $this->copyStubToApp('DefaultResourcePage', $listResourcePagePath, [
                'baseResourcePage'      => 'Dasher\\Resources\\Pages\\ListRecords',
                'baseResourcePageClass' => 'ListRecords',
                'namespace'             => "App\\Dasher\\Resources\\{$resource}\\Pages",
                'resource'              => $resource,
                'resourceClass'         => $resourceClass,
                'resourcePageClass'     => $listResourcePageClass,
            ]);

            $this->copyStubToApp('DefaultResourcePage', $createResourcePagePath, [
                'baseResourcePage'      => 'Dasher\\Resources\\Pages\\CreateRecord',
                'baseResourcePageClass' => 'CreateRecord',
                'namespace'             => "App\\Dasher\\Resources\\{$resource}\\Pages",
                'resource'              => $resource,
                'resourceClass'         => $resourceClass,
                'resourcePageClass'     => $createResourcePageClass,
            ]);

            $this->copyStubToApp('DefaultResourcePage', $editResourcePagePath, [
                'baseResourcePage'      => 'Dasher\\Resources\\Pages\\EditRecord',
                'baseResourcePageClass' => 'EditRecord',
                'namespace'             => "App\\Dasher\\Resources\\{$resource}\\Pages",
                'resource'              => $resource,
                'resourceClass'         => $resourceClass,
                'resourcePageClass'     => $editResourcePageClass,
            ]);
        }

        $this->info("Successfully created {$resource}!");

        return static::SUCCESS;
    }
}
