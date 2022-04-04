<?php

declare(strict_types=1);

namespace Dasher\Commands\Aliases;

class MakeUserCommand extends \Dasher\Commands\MakeUserCommand
{
    protected $hidden    = true;
    protected $signature = 'dasher:user';
}
