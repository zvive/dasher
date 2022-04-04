<?php

declare(strict_types=1);

namespace Dasher\Commands\Aliases;

class MakeWidgetCommand extends \Dasher\Commands\MakeWidgetCommand
{
    protected $hidden    = true;
    protected $signature = 'dasher:widget {name?} {--R|resource=} {--C|chart} {--T|table} {--S|stats-overview} {--F|force}';
}
