<?php

declare(strict_types=1);

namespace Dasher\Commands\Aliases;

class MakeHasManyThroughCommand extends \Dasher\Commands\MakeHasManyThroughCommand
{
    protected $hidden    = true;
    protected $signature = 'filament:has-many-through {resource?} {relationship?} {recordTitleAttribute?} {--F|force}';
}
