<x-dasher::page class="dasher-dashboard-page">
  @if ($widgets = \Filament\Facades\Filament::getWidgets())
    <x-dasher::widgets :widgets="$widgets" />
  @endif
</x-dasher::page>
