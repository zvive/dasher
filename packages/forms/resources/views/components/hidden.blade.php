<input id="{{ $getId() }}" {!! $isRequired() ? 'required' : null !!} type="hidden"
  {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
  {{ $attributes->merge($getExtraAttributes())->class(['dasher-forms-hidden-component']) }}
  dusk="dasher.forms.{{ $getStatePath() }}" />
