<?php

namespace Dasher\Commands\Aliases;

use Dasher\Commands;

class MakeResourceCommand extends Commands\MakeResourceCommand
{
    protected $hidden = true;

    protected $signature = 'filament:resource {name?} {--G|generate} {--S|simple} {--F|force}';
}
