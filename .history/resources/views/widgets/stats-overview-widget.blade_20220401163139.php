<x-dasher::widget class="dasher-stats-overview-widget">
  <div {!! ($pollingInterval = $this->getPollingInterval()) ? "wire:poll.{$pollingInterval}" : '' !!}>
    <x-dasher::stats :columns="$this->getColumns()">
      @foreach ($this->getCachedCards() as $card)
        {{ $card }}
      @endforeach
    </x-dasher::stats>
  </div>
</x-dasher::widget>
