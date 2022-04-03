<x-dasher::page class="dasher-resources-create-record-page">
  <x-dasher::form wire:submit.prevent="create">
    {{ $this->form }}

    <x-dasher::form.actions :actions="$this->getCachedFormActions()" />
  </x-dasher::form>
</x-dasher::page>
