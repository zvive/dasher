<div class="flex items-center justify-center min-h-screen dasher-login-page">
  <div class="p-2 max-w-md space-y-8 w-screen">
    <form wire:submit.prevent="authenticate" @class([
        'bg-white space-y-8 shadow border border-gray-300 rounded-2xl p-8',
        'dark:bg-gray-800 dark:border-gray-700' => config('dasher.dark_mode'),
    ])>
      <div class="w-full flex justify-center">
        <x-dasher::brand />
      </div>

      <h2 class="font-bold tracking-tight text-center text-2xl">
        {{ __('dasher::login.heading') }}
      </h2>

      {{ $this->form }}

      <x-dasher::button type="submit" form="authenticate" class="w-full">
        {{ __('dasher::login.buttons.submit.label') }}
      </x-dasher::button>
    </form>

    <x-dasher::footer />
  </div>
</div>
