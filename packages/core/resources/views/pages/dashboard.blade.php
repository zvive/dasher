<x-dasher::page class="dasher-dashboard-page">
  @if ($widgets = \Dasher\Facades\Dasher::getWidgets())
    <x-dasher::widgets :widgets="$widgets" />
  @endif
</x-dasher::page>
