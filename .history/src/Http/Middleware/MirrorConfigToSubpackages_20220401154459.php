<?php

declare(strict_types=1);

namespace Dasher\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MirrorConfigToSubpackages
{
    public function handle(Request $request, Closure $next)
    {
        $config = \config();

        $darkMode = $config->get('dasher.dark_mode');
        $config->set('forms.dark_mode', $darkMode);
        $config->set('tables.dark_mode', $darkMode);

        $defaultFilesystemDisk = $config->get('dasher.default_filesystem_disk');
        $config->set('forms.default_filesystem_disk', $defaultFilesystemDisk);
        $config->set('tables.default_filesystem_disk', $defaultFilesystemDisk);

        return $next($request);
    }
}
