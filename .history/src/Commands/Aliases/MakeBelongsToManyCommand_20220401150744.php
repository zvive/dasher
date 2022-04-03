<?php

declare(strict_types=1);

namespace Dasher\Commands\Aliases;

class MakeBelongsToManyCommand extends \Dasher\Commands\MakeBelongsToManyCommand
{
    protected $hidden    = true;
    protected $signature = 'filament:belongs-to-many {resource?} {relationship?} {recordTitleAttribute?} {--F|force}';
}
