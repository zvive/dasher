<?php

declare(strict_types=1);

namespace Dasher\Profile;

use Livewire\Livewire;
use Dasher\Profile\Pages\Profile;

class ProfileServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-profile';

    public function packageBooted() : void
    {
        parent::packageBooted();

        Livewire::component(Profile::getName(), Profile::class);
    }
}
