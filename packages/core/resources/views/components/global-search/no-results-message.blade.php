<div
  {{ $attributes->class([
      'px-6 py-4 dasher-global-search-no-results-message',
      'dark:text-gray-200' => config('dasher.dark_mode'),
  ]) }}>
  {{ __('dasher::global-search.no_results_message') }}
</div>
