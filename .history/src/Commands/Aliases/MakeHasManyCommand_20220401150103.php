<?php

namespace Dasher\Commands\Aliases;

use Dasher\Commands;

class MakeHasManyCommand extends Commands\MakeHasManyCommand
{
    protected $hidden = true;

    protected $signature = 'filament:has-many {resource?} {relationship?} {recordTitleAttribute?} {--F|force}';
}
