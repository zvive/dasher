<?php

namespace Dasher\Tests\Admin\Resources;

use Dasher\Tests\Models\User;
use Dasher\Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    protected function getPackageProviders($app): array
    {
        return array_merge(parent::getPackageProviders($app), [
            ResourcesServiceProvider::class,
        ]);
    }
}
