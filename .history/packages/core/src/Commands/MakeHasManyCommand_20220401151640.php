<?php

declare(strict_types=1);

namespace Dasher\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeHasManyCommand extends Command
{
    use Concerns\CanManipulateFiles;
    use Concerns\CanValidateInput;
    protected $description = 'Creates a Dasher HasMany relation manager class for a resource.';
    protected $signature   = 'z:dasher-has-many {resource?} {relationship?} {recordTitleAttribute?} {--F|force}';

    public function handle() : int
    {
        $resource = (string) Str::of($this->argument('resource') ?? $this->askRequired('Resource (e.g. `UserResource`)', 'resource'))
            ->studly()
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');

        if ( ! Str::of($resource)->endsWith('Resource')) {
            $resource .= 'Resource';
        }

        $relationship = (string) Str::of($this->argument('relationship') ?? $this->askRequired('Relationship (e.g. `blogPosts`)', 'relationship'))
            ->trim(' ');
        $managerClass = (string) Str::of($relationship)
            ->studly()
            ->append('RelationManager');

        $recordTitleAttribute = (string) Str::of($this->argument('recordTitleAttribute') ?? $this->askRequired('Title attribute (e.g. `title`)', 'title attribute'))
            ->trim(' ');

        $path = \app_path(
            (string) Str::of($managerClass)
                ->prepend("Dasher\\Resources\\{$resource}\\RelationManagers\\")
                ->replace('\\', '/')
                ->append('.php'),
        );

        if ( ! $this->option('force') && $this->checkForCollision([
            $path,
        ])) {
            return static::INVALID;
        }

        $this->copyStubToApp('HasManyRelationManager', $path, [
            'namespace'            => "App\\Dasher\\Resources\\{$resource}\\RelationManagers",
            'managerClass'         => $managerClass,
            'recordTitleAttribute' => $recordTitleAttribute,
            'relationship'         => $relationship,
        ]);

        $this->info("Successfully created {$managerClass}!");

        $this->info("Make sure to register the relation in `{$resource}::getRelations()`.");

        return static::SUCCESS;
    }
}
