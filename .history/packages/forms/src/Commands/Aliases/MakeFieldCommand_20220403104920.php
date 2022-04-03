<?php

declare(strict_types=1);

namespace Dasher\Forms\Commands\Aliases;

use Dasher\Forms\Commands;

class MakeFieldCommand extends Commands\MakeFieldCommand
{
    protected $hidden    = true;
    protected $signature = 'forms:field {name} {--F|force}';
}
