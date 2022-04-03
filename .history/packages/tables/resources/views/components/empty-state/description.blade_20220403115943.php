<p
  {{ $attributes->class([
      'text-sm font-medium text-gray-500 dasher-tables-empty-state-description',
      'dark:text-gray-400' => config('tables.dark_mode'),
  ]) }}>
  {{ $slot }}
</p>
