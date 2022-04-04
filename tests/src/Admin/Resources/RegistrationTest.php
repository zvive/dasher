<?php

use Dasher\Facades\Dasher;
use Dasher\Tests\Admin\Fixtures\Resources\PostResource;
use Dasher\Tests\Admin\Resources\TestCase;

uses(TestCase::class);

it('can register resources', function () {
    expect(Dasher::getResources())
        ->toContain(PostResource::class);
});
