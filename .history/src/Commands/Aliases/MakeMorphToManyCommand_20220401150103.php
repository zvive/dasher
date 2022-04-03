<?php

namespace Dasher\Commands\Aliases;

use Dasher\Commands;

class MakeMorphToManyCommand extends Commands\MakeMorphToManyCommand
{
    protected $hidden = true;

    protected $signature = 'filament:morph-to-many {resource?} {relationship?} {recordTitleAttribute?} {--F|force}';
}
