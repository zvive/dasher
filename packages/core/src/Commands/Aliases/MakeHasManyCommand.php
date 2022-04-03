<?php

declare(strict_types=1);

namespace Dasher\Commands\Aliases;

class MakeHasManyCommand extends \Dasher\Commands\MakeHasManyCommand
{
    protected $hidden    = true;
    protected $signature = 'dasher:has-many {resource?} {relationship?} {recordTitleAttribute?} {--F|force}';
}
