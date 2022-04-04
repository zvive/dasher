@if (filled($brand = config('dasher.brand')))
  <div @class([
      'text-xl font-bold tracking-tight dasher-brand',
      'dark:text-white' => config('dasher.dark_mode'),
  ])>
    {{     \Illuminate\Support\Str::of($brand)->snake()->upper()->explode('_')->map(fn(string $string) => \Illuminate\Support\Str::substr($string, 0, 1))->take(2)->implode('') }}
  </div>
@endif
