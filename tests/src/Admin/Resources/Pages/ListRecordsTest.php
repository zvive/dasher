<?php

use Dasher\Tests\Admin\Fixtures\Resources\PostResource;
use Dasher\Tests\Admin\Resources\TestCase;

uses(TestCase::class);

it('can render page', function () {
    $this->get(PostResource::getUrl('index'))->assertSuccessful();
});
