<div class="pointer-events-none fixed bottom-6 right-6 z-20">
    <div
        x-cloak
        x-data="{
            notifications: [],
            asyncNotifications: [],
            closedNotifications: 0,
            toggleNotifications() {
                this.closedNotifications > 0
                    ? $dispatch('open-all-notifications')
                    : $dispatch('close-all-notifications')
            },
        }"
        x-init="
            () => {
                window.addEventListener('create-notification', (event) => {
                    asyncNotifications.push({
                        type: event.detail.type || 'success',
                        content: event.detail.content || '',
                        duration: event.detail.duration || 5000,
                    })
                })

                window.addEventListener('notification-opened', () => {
                    closedNotifications--
                })
                window.addEventListener('notification-closed', () => {
                    closedNotifications++
                })
            }
        "
        class="pointer-events-auto flex max-w-3xl items-end justify-end gap-4"
    >
        <div class="flex flex-col items-end space-y-4">
            {{ $slot }}

            <template x-for="notification in asyncNotifications">
                <x-chief::notifications.item
                    x-data="{ type: notification.type, duration: notification.duration, isOpen: true }"
                >
                    <div x-html="notification.content"></div>
                </x-chief::notifications.item>
            </template>
        </div>

        <div
            x-on:click="toggleNotifications"
            x-show="notifications.length + asyncNotifications.length > 0"
            class="relative cursor-pointer rounded-full border border-grey-100 bg-white p-3 text-grey-900 shadow-lg hover:bg-grey-50"
        >
            <x-chief::icon.bell class="size-5" />

            <div
                x-show="closedNotifications > 0"
                class="absolute -bottom-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-primary-500 to-primary-600"
            >
                <span class="text-xs font-bold leading-none text-white" x-text="closedNotifications"></span>
            </div>
        </div>
    </div>
</div>
