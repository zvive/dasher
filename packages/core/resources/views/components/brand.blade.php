@if (filled($brand = config('dasher.brand')))
  <div @class([
      'text-xl font-bold tracking-tight dasher-brand',
      'dark:text-white' => config('dasher.dark_mode'),
  ])>
    {{ $brand }}
  </div>
@endif
