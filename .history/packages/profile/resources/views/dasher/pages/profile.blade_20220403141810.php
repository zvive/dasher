<x-dasher::page>
  <form wire:submit.prevent="submit" class="space-y-6">
    {{ $this->form }}

    <div class="flex flex-wrap items-center gap-4 justify-start">
      <x-dasher::button type="submit">
        Save
      </x-dasher::button>

      <x-dasher::button type="button" color="secondary" tag="a" :href="$this->cancel_button_url">
        Cancel
      </x-dasher::button>
    </div>
  </form>
</x-dasher::page>
