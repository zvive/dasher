<div class="flex items-center dasher-global-search">
  <x-dasher::global-search.start />

  @if ($this->isEnabled())
    <div class="relative">
      <x-dasher::global-search.input />

      @if ($results !== null)
        <x-dasher::global-search.results-container :results="$results" />
      @endif
    </div>
  @endif

  <x-dasher::global-search.end />
</div>
