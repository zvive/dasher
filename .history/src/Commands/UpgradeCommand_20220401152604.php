<?php

declare(strict_types=1);

namespace Dasher\Commands;

use Illuminate\Console\Command;

class UpgradeCommand extends Command
{
    use Concerns\CanManipulateFiles;
    protected $description = 'Upgrade Filament to the latest version.';
    protected $signature   = 'z:dash-upgrade';

    public function handle() : int
    {
        foreach ([
            'config:clear',
            'livewire:discover',
            'route:clear',
            'view:clear',
        ] as $command) {
            $this->call($command);
        }

        $this->info('Successfully upgraded!');

        return static::SUCCESS;
    }
}
