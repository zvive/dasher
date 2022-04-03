<x-dasher::page :widget-record="$record" class="dasher-resources-view-record-page">
  {{ $this->form }}

  @if (count($relationships = $this->getRelationships()))
    <x-dasher::hr />

    <x-dasher::resources.relation-managers :active-manager="$activeRelationManager" :managers="$relationships" :owner-record="$record" />
  @endif
</x-dasher::page>
