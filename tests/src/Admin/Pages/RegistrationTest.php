<?php

use Dasher\Facades\Dasher;
use Dasher\Tests\Admin\Fixtures\Pages\Settings;
use Dasher\Tests\Admin\Pages\TestCase;

uses(TestCase::class);

it('can register pages', function () {
    expect(Dasher::getPages())
        ->toContain(Settings::class);
});
