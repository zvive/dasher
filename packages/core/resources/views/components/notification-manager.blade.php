<div x-data="{
    notifications: {{ json_encode(session()->pull('notifications', [])) }},
    add: function(event) {
        this.notifications.push(event.detail)
    },
    remove: function(notification) {
        this.notifications = this.notifications.filter(i => i.id !== notification.id)
    },
}" x-on:notify.window="add($event)" @class([
    'flex fixed inset-0 z-50 p-3 pointer-events-none dasher-notifications',
    'justify-start' =>
        config('dasher.layout.notifications.alignment') === 'left',
    'justify-center' =>
        config('dasher.layout.notifications.alignment') === 'center',
    'justify-end' =>
        config('dasher.layout.notifications.alignment') === 'right',
    'items-start' =>
        config('dasher.layout.notifications.vertical_alignment') === 'top',
    'items-center' =>
        config('dasher.layout.notifications.vertical_alignment') === 'center',
    'items-end' =>
        config('dasher.layout.notifications.vertical_alignment') === 'bottom',
])
  role="status" aria-live="polite" wire:ignore>
  <div class="space-y-4">
    <template x-for="notification in notifications" :key="notification.id">
      <x-dasher::notification />
    </template>
  </div>
</div>
