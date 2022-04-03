<x-dasher::button :form="$getForm()" :type="$canSubmitForm() ? 'submit' : 'button'"
  :wire:click="$getAction()" :x-on:click="$canCancelAction() ? 'isOpen = false' : null"
  :color="$getColor()" :outlined="$isOutlined()" :icon="$getIcon()"
  :icon-position="$getIconPosition()" class="dasher-page-modal-button-action">
  {{ $getLabel() }}
</x-dasher::button>
