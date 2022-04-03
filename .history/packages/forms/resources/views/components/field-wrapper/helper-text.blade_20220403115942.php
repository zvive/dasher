<div
  {{ $attributes->class([
      'text-sm text-gray-600 dasher-forms-field-wrapper-helper-text',
      'dark:text-gray-300' => config('forms.dark_mode'),
  ]) }}>
  {{ $slot }}
</div>
