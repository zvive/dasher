<?php

declare(strict_types=1);

namespace Dasher\Commands\Aliases;

class MakeMorphToManyCommand extends \Dasher\Commands\MakeMorphToManyCommand
{
    protected $hidden    = true;
    protected $signature = 'dasher:morph-to-many {resource?} {relationship?} {recordTitleAttribute?} {--F|force}';
}
