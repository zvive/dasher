@props(['actions'])

<x-dasher::pages.actions :actions="$actions"
  :align="config('dasher.layout.forms.actions.alignment')" class="dasher-form-actions" />
