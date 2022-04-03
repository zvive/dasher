<x-dasher::page :widget-record="$record" class="dasher-resources-view-record-page">
  {{ $this->form }}

  @if (count($relationManagers = $this->getRelationManagers()))
    <x-dasher::hr />

    <x-dasher::resources.relation-managers :active-manager="$activeRelationManager"
      :managers="$relationManagers" :owner-record="$record" />
  @endif
</x-dasher::page>
