<x-dasher::widget class="dasher-account-widget">
  <x-dasher::card>
    @php
      $user = \Dasher\Facades\Dasher::auth()->user();
    @endphp

    <div class="h-12 flex items-center space-x-4 rtl:space-x-reverse">
      <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center"
        style="background-image: url('{{ \Dasher\Facades\Dasher::getUserAvatarUrl($user) }}')">
      </div>

      <div>
        <h2 class="text-lg sm:text-xl font-bold tracking-tight">
          {{ __('dasher::widgets/account-widget.welcome', ['user' => \Dasher\Facades\Dasher::getUserName($user)]) }}
        </h2>

        <form action="{{ route('dasher.auth.logout') }}" method="post" class="text-sm">
          @csrf

          <button type="submit" @class([
              'text-gray-600 hover:text-primary-500 focus:outline-none focus:underline',
              'dark:text-gray-300 dark:hover:text-primary-500' => config(
                  'dasher.dark_mode'
              ),
          ])>
            {{ __('dasher::widgets/account-widget.buttons.logout.label') }}
          </button>
        </form>
      </div>
    </div>
  </x-dasher::card>
</x-dasher::widget>
