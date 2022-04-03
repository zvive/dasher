<?php

declare(strict_types=1);

namespace Dasher\Commands\Aliases;

class MakePageCommand extends \Dasher\Commands\MakePageCommand
{
    protected $hidden    = true;
    protected $signature = 'dasher:page {name?} {--R|resource=} {--F|force}';
}
