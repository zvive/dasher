<?php

declare(strict_types=1);

namespace Dasher\Tests\Admin\Pages;

use Dasher\Tests\Models\User;
use Dasher\Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    protected function getPackageProviders($app) : array
    {
        return \array_merge(parent::getPackageProviders($app), [
            PagesServiceProvider::class,
        ]);
    }
}
