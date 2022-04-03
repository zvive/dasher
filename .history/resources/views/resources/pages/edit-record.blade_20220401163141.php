<x-dasher::page :widget-record="$record" class="dasher-resources-edit-record-page">
  <x-dasher::form wire:submit.prevent="save">
    {{ $this->form }}

    <x-dasher::form.actions :actions="$this->getCachedFormActions()" />
  </x-dasher::form>

  @if (count($relationManagers = $this->getRelationManagers()))
    <x-dasher::hr />

    <x-dasher::resources.relation-managers :active-manager="$activeRelationManager"
      :managers="$relationManagers" :owner-record="$record" />
  @endif
</x-dasher::page>
