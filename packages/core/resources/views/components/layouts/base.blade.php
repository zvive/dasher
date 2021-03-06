@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  dir="{{ __('dasher::layout.direction') ?? 'ltr' }}"
  class="dasher antialiased bg-gray-100 js-focus-visible">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @foreach (\Dasher\Facades\Dasher::getMeta() as $tag)
    {{ $tag }}
  @endforeach

  @if ($favicon = config('dasher.favicon'))
    <link rel="icon" href="{{ $favicon }}">
  @endif

  <title>{{ $title ? "{$title} - " : null }} {{ config('app.name') }}</title>

  <style>
    [x-cloak=""],
    [x-cloak="1"] {
      display: none !important;
    }

    @media (max-width: 1023px) {
      [x-cloak="-lg"] {
        display: none !important;
      }
    }

    @media (min-width: 1024px) {
      [x-cloak="lg"] {
        display: none !important;
      }
    }

  </style>

  @livewireStyles

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap"
    rel="stylesheet" />

  @foreach (\Dasher\Facades\Dasher::getStyles() as $name => $path)
    @if (Str::of($path)->startsWith(['http://', 'https://']))
      <link rel="stylesheet" href="{{ $path }}" />
    @else
      <link rel="stylesheet"
        href="{{ route('dasher.asset', [
            'file' => "{$name}.css",
        ]) }}" />
    @endif
  @endforeach

  <link rel="stylesheet" href="{{ \Dasher\Facades\Dasher::getThemeUrl() }}" />

  @if (config('dasher.dark_mode'))
    <script>
      const theme = localStorage.getItem('theme')

      if ((theme === 'dark') || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark')
      }
    </script>
  @endif
</head>

<body @class([
    'bg-gray-100 text-gray-900 dasher-body',
    'dark:text-gray-100 dark:bg-gray-900' => config('dasher.dark_mode'),
])>
  {{ $slot }}

  @livewireScripts

  <script>
    window.dasherData = @json(\Dasher\Facades\Dasher::getScriptData());
  </script>

  @foreach (\Dasher\Facades\Dasher::getBeforeCoreScripts() as $name => $path)
    @if (Str::of($path)->startsWith(['http://', 'https://']))
      <script src="{{ $path }}"></script>
    @else
      <script
            src="{{ route('dasher.asset', [
                'file' => "{$name}.js",
            ]) }}">
      </script>
    @endif
  @endforeach

  @stack('beforeCoreScripts')

  <script
    src="{{ route('dasher.asset', [
        'id' => Dasher\get_asset_id('app.js'),
        'file' => 'app.js',
    ]) }}">
  </script>

  @foreach (\Dasher\Facades\Dasher::getScripts() as $name => $path)
    @if (Str::of($path)->startsWith(['http://', 'https://']))
      <script src="{{ $path }}"></script>
    @else
      <script
            src="{{ route('dasher.asset', [
                'file' => "{$name}.js",
            ]) }}">
      </script>
    @endif
  @endforeach

  @stack('scripts')
</body>

</html>
