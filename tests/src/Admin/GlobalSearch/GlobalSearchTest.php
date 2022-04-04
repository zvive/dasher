<?php

use Dasher\Facades\Dasher;
use Dasher\GlobalSearch\Contracts\GlobalSearchProvider;
use Dasher\GlobalSearch\GlobalSearchResult;
use Dasher\GlobalSearch\GlobalSearchResults;
use Dasher\Http\Livewire\GlobalSearch;
use Dasher\Tests\Admin\GlobalSearch\TestCase;
use Dasher\Tests\Models\Post;
use Livewire\Livewire;

uses(TestCase::class);

it('can be mounted', function () {
    Livewire::test(GlobalSearch::class)
        ->assertOk();
});

it('can retrieve search results', function () {
    $post = Post::factory()->create();

    Livewire::test(GlobalSearch::class)
        ->set('searchQuery', $post->title)
        ->assertDispatchedBrowserEvent('open-global-search-results')
        ->assertSee($post->title);
});

it('can retrieve results via custom search provider', function () {
    Dasher::globalSearchProvider(CustomSearchProvider::class);

    Livewire::test(GlobalSearch::class)
        ->set('searchQuery', 'foo')
        ->assertDispatchedBrowserEvent('open-global-search-results')
        ->assertSee(['foo', 'bar', 'baz']);
});

class CustomSearchProvider implements GlobalSearchProvider
{
    public function getResults(string $query): ?GlobalSearchResults
    {
        return GlobalSearchResults::make()
            ->category('foobarbaz', [
                new GlobalSearchResult(title: 'foo', url: '#', details: []),
                new GlobalSearchResult(title: 'bar', url: '#', details: []),
                new GlobalSearchResult(title: 'baz', url: '#', details: []),
            ]);
    }
}
