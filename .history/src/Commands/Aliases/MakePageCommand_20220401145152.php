<?php

namespace Dasher\Commands\Aliases;

use Filament\Commands;

class MakePageCommand extends Commands\MakePageCommand
{
    protected $hidden = true;

    protected $signature = 'filament:page {name?} {--R|resource=} {--F|force}';
}
