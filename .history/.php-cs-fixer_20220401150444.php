<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use Zvive\Fixer\SharedConfig;
use Zvive\Fixer\Rulesets\ZviveRuleset;
use Zvive\Fixer\Finders\LaravelProjectFinder;

$finder = LaravelProjectFinder::create(__DIR__);

return SharedConfig::create($finder, new ZviveRuleset([
    'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
]));
