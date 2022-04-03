<?php

namespace Dasher\Commands\Aliases;

use Dasher\Commands;

class MakeSettingsPageCommand extends Commands\MakeSettingsPageCommand
{
    protected $hidden = true;

    protected $signature = 'filament:settings-page {name?} {settingsClass?}';
}
