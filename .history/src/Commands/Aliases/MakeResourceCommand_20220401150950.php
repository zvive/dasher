<?php

declare(strict_types=1);

namespace Dasher\Commands\Aliases;

class MakeResourceCommand extends Dasher\Commands\MakeResourceCommand
{
    protected $hidden    = true;
    protected $signature = 'dasher:resource {name?} {--G|generate} {--S|simple} {--F|force}';
}
