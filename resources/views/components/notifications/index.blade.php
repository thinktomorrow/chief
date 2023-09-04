<div class="fixed z-20 pointer-events-none bottom-6 right-6">
    <div
        x-cloak
        x-data="{
            notifications: [],
            asyncNotifications: [],
            closedNotifications: 0,
            toggleNotifications() {
                this.closedNotifications > 0 ? $dispatch('open-all-notifications') : $dispatch('close-all-notifications');
            },
        }"
        x-init="() => {
            window.addEventListener('create-notification', (event) => {
                asyncNotifications.push({
                    type: event.detail.type || 'success',
                    content: event.detail.content || '',
                    duration: event.detail.duration || 5000,
                });
            });

            window.addEventListener('notification-opened', () => { closedNotifications-- });
            window.addEventListener('notification-closed', () => { closedNotifications++ });
        }"
        class="flex items-end justify-end max-w-3xl gap-4 pointer-events-auto"
    >
        <div class="flex flex-col items-end space-y-4">
            {{ $slot }}

            <template x-for="notification in asyncNotifications">
                <x-chief::notifications.item x-data="{ type: notification.type, duration: notification.duration, isOpen: true }">
                    <div x-html="notification.content"></div>
                </x-chief::notifications.item>
            </template>
        </div>

        <div
            x-on:click="toggleNotifications"
            x-show="notifications.length + asyncNotifications.length > 0"
            class="relative p-3 bg-white border rounded-full shadow-lg cursor-pointer text-grey-900 border-grey-100 hover:bg-grey-50 pop"
        >
            <svg class="w-5 h-5"><use xlink:href="#icon-bell"></use></svg>

            <div x-show="closedNotifications > 0" class="absolute flex items-center justify-center w-6 h-6 rounded-full -bottom-2 -right-2 bg-gradient-to-br from-primary-500 to-primary-600 pop">
                <span class="text-xs font-bold leading-none text-white" x-text="closedNotifications"></span>
            </div>
        </div>
    </div>
</div>
