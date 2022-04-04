<?php

namespace Dasher\Tables\Commands\Aliases;

use Dasher\Tables\Commands;

class MakeColumnCommand extends Commands\MakeColumnCommand
{
    protected $hidden = true;

    protected $signature = 'tables:column {name} {--F|force}';
}
