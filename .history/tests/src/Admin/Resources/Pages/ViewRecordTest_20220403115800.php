<?php

use Dasher\Facades\Dasher;
use Dasher\Tests\Admin\Fixtures\Resources\PostResource;
use Dasher\Tests\Admin\Resources\TestCase;
use Dasher\Tests\Models\Post;
use function Pest\Livewire\livewire;

uses(TestCase::class);

beforeEach(function () {
    Dasher::registerResources([
        PostResource::class,
    ]);
});

it('can render page', function () {
    $this->get(PostResource::getUrl('view', [
        'record' => Post::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data', function () {
    $post = Post::factory()->create();

    livewire(PostResource\Pages\ViewPost::class, [
        'record' => $post->getKey(),
    ])
        ->assertSet('data.author_id', $post->author->getKey())
        ->assertSet('data.content', $post->content)
        ->assertSet('data.tags', $post->tags)
        ->assertSet('data.title', $post->title);
});
