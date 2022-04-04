<aside x-data="{}"
  @if (config('dasher.layout.sidebar.is_collapsible_on_desktop')) x-cloak
        x-bind:class="$store.sidebar.isOpen ? 'translate-x-0 lg:max-w-[20em]' : '-translate-x-full lg:translate-x-0 lg:max-w-[5.4em] rtl:lg:-translate-x-0 rtl:translate-x-full'"
    @else
        x-cloak="-lg"
        x-bind:class="$store.sidebar.isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0 rtl:lg:-translate-x-0 rtl:translate-x-full'" @endif
  @class([
      'fixed inset-y-0 left-0 rtl:left-auto rtl:right-0 z-20 flex flex-col h-screen overflow-hidden shadow-2xl transition-all bg-white dasher-sidebar lg:border-r w-80 lg:z-0',
      'lg:translate-x-0' => !config(
          'dasher.layout.sidebar.is_collapsible_on_desktop'
      ),
      'dark:bg-gray-800 dark:border-gray-700' => config('dasher.dark_mode'),
  ])>
  <header @class([
      'border-b h-[4rem] shrink-0 px-6 flex items-center dasher-sidebar-header',
      'dark:border-gray-700' => config('dasher.dark_mode'),
  ])>
    <a href="{{ config('dasher.home_url') }}"
      @if (config('dasher.layout.sidebar.is_collapsible_on_desktop')) x-show="$store.sidebar.isOpen"
                x-transition:enter="lg:transition delay-100"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" @endif>
      <x-dasher::brand />
    </a>

    @if (config('dasher.layout.sidebar.is_collapsible_on_desktop'))
      <a class="block w-full text-center" href="{{ config('dasher.home_url') }}"
        x-show="! $store.sidebar.isOpen" x-transition:enter="lg:transition delay-100"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <x-dasher::brand-icon />
      </a>
    @endif
  </header>

  <nav class="flex-1 overflow-y-auto py-6 dasher-sidebar-nav">
    <x-dasher::layouts.app.sidebar.start />

    <ul class="space-y-6 px-6">
      @foreach (\Dasher\Facades\Dasher::getNavigation() as $group => $items)
        <x-dasher::layouts.app.sidebar.group :label="$group">
          @foreach ($items as $item)
            <x-dasher::layouts.app.sidebar.item :active="$item->isActive()" :icon="$item->getIcon()"
              :url="$item->getUrl()" :badge="$item->getBadge()"
              :shouldOpenUrlInNewTab="$item->shouldOpenUrlInNewTab()">
              {{ $item->getLabel() }}
            </x-dasher::layouts.app.sidebar.item>
          @endforeach
        </x-dasher::layouts.app.sidebar.group>

        @if (!$loop->last)
          <li>
            <div @class([
                'border-t -mr-6 rtl:-mr-auto rtl:-ml-6',
                'dark:border-gray-700' => config('dasher.dark_mode'),
            ])></div>
          </li>
        @endif
      @endforeach
    </ul>

    <x-dasher::layouts.app.sidebar.end />
  </nav>

  <x-dasher::layouts.app.sidebar.footer />
</aside>
