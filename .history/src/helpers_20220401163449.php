<?php

declare(strict_types=1);

namespace Dasher;

use Illuminate\Support\Str;

if ( ! \function_exists('Dasher\get_asset_id')) {
    function get_asset_id(string $file, string $manifestPath = null) : ?string
    {
        $manifestPath ??= __DIR__.'/../dist/mix-manifest.json';

        if ( ! \file_exists($manifestPath)) {
            return null;
        }

        $manifest = \json_decode(\file_get_contents($manifestPath), true);

        $file = "/{$file}";

        if ( ! \array_key_exists($file, $manifest)) {
            return null;
        }

        $file = $manifest[$file];

        if ( ! \str_contains($file, 'id=')) {
            return null;
        }

        return (string) Str::of($file)->after('id=');
    }
}
