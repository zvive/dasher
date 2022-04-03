<?php

declare(strict_types=1);

namespace Dasher\Commands\Aliases;

class MakeMorphManyCommand extends \Dasher\Commands\MakeMorphManyCommand
{
    protected $hidden    = true;
    protected $signature = 'filament:morph-many {resource?} {relationship?} {recordTitleAttribute?} {--F|force}';
}
