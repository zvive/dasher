<?php

namespace Dasher\Tests\Admin\Fixtures\Resources\PostResource\Pages;

use Dasher\Resources\Pages\ListRecords;
use Dasher\Tests\Admin\Fixtures\Resources\PostResource;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;
}
