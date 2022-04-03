<x-dasher::page>
  <x-dasher::form wire:submit.prevent="save">
    {{ $this->form }}

    <x-dasher::pages.actions :actions="$this->getFormActions()" />
  </x-dasher::form>
</x-dasher::page>
