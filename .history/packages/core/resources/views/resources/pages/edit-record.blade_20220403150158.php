<x-dasher::page :widget-record="$record" class="dasher-resources-edit-record-page">
  <x-dasher::form wire:submit.prevent="save">
    {{ $this->form }}

    <x-dasher::form.actions :actions="$this->getCachedFormActions()" />
  </x-dasher::form>

  @if (count($relationManagers = $this->getRelationships()))
    <x-dasher::hr />

    <x-dasher::resources.relation-managers :active-manager="$activeRelationship" :relationships="$relationships" :owner-record="$record" />
  @endif
</x-dasher::page>
