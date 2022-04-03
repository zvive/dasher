<?php

declare(strict_types=1);

namespace Dasher;

use Livewire\Livewire;
use Dasher\Profile\Pages\Profile;

class DasherProfileServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-profile';

    public function packageBooted() : void
    {
        parent::packageBooted();

        Livewire::component(Profile::getName(), Profile::class);
    }
}
