<?php

declare(strict_types=1);

namespace Dasher\Forms\Commands\Aliases;

use Dasher\Forms\Commands;

class MakeLayoutComponentCommand extends Commands\MakeLayoutComponentCommand
{
    protected $hidden    = true;
    protected $signature = 'forms:layout {name} {dashboard?} {--F|force}';
}
