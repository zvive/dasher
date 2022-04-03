<?php

namespace Dasher\Commands\Aliases;

use Filament\Commands;

class MakeHasManyThroughCommand extends Commands\MakeHasManyThroughCommand
{
    protected $hidden = true;

    protected $signature = 'filament:has-many-through {resource?} {relationship?} {recordTitleAttribute?} {--F|force}';
}
