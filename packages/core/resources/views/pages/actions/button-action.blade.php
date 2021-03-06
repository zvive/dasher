@php
$action = $getAction();
$url = $getUrl();

if (!$action) {
    $wireClickAction = null;
} elseif ($shouldOpenModal() || $action instanceof \Closure) {
    $wireClickAction = "mountAction('{$getName()}')";
} else {
    $wireClickAction = $action;
}
@endphp

<x-dasher::button :form="$getForm()" :tag="((! $action) && $url) ? 'a' : 'button'"
  :wire:click="$isEnabled() ? $wireClickAction : null" :href="$isEnabled() ? $getUrl() : null"
  :target="$shouldOpenUrlInNewTab() ? '_blank' : null"
  :type="$canSubmitForm() ? 'submit' : 'button'" :color="$getColor()" :tooltip="$getTooltip()"
  :outlined="$isOutlined()" :disabled="$isDisabled()" :icon="$getIcon()"
  :icon-position="$getIconPosition()" class="dasher-page-button-action">
  {{ $getLabel() }}
</x-dasher::button>
