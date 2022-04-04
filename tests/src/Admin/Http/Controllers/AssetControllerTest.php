<?php

use Dasher\Tests\TestCase;

use function Pest\Laravel\get;

uses(TestCase::class);

it('can successfully load the assets', function (string $file) {
    get(route('dasher.asset', ['file' => $file]))->assertOk();
})->with([
    ['app.css'],
    ['app.css.map'],
    ['app.js'],
    ['app.js.map'],
]);
