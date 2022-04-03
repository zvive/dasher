<?php

namespace Dasher\Http\Livewire;

use Filament\Facades\Filament;
use Filament\GlobalSearch\GlobalSearchResults;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $searchQuery = '';

    public function getResults(): ?GlobalSearchResults
    {
        $searchQuery = trim($this->searchQuery);

        if ($searchQuery === '') {
            return null;
        }

        $results = Filament::getGlobalSearchProvider()->getResults($this->searchQuery);

        if ($results === null) {
            return $results;
        }

        $this->dispatchBrowserEvent('open-global-search-results');

        return $results;
    }

    protected function isEnabled(): bool
    {
        foreach (Filament::getResources() as $resource) {
            if ($resource::canGloballySearch()) {
                return true;
            }
        }

        return false;
    }

    public function render()
    {
        return view('filament::components.global-search.index', [
            'results' => $this->getResults(),
        ]);
    }
}
