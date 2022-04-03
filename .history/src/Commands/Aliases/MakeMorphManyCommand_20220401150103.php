<?php

namespace Dasher\Commands\Aliases;

use Dasher\Commands;

class MakeMorphManyCommand extends Commands\MakeMorphManyCommand
{
    protected $hidden = true;

    protected $signature = 'filament:morph-many {resource?} {relationship?} {recordTitleAttribute?} {--F|force}';
}
