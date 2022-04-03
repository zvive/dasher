<?php

declare(strict_types=1);

namespace Dasher\Http\Livewire;

use Livewire\Component;
use Dasher\Facades\Dasher;
use Dasher\GlobalSearch\GlobalSearchResults;

class GlobalSearch extends Component
{
    public $searchQuery = '';

    public function getResults() : ?GlobalSearchResults
    {
        $searchQuery = \trim($this->searchQuery);

        if ($searchQuery === '') {
            return null;
        }

        $results = Dasher::getGlobalSearchProvider()->getResults($this->searchQuery);

        if ($results === null) {
            return $results;
        }

        $this->dispatchBrowserEvent('open-global-search-results');

        return $results;
    }

    protected function isEnabled() : bool
    {
        foreach (Dasher::getResources() as $resource) {
            if ($resource::canGloballySearch()) {
                return true;
            }
        }

        return false;
    }

    public function render()
    {
        return \view('filament::components.global-search.index', [
            'results' => $this->getResults(),
        ]);
    }
}
