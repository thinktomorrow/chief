<div class="pointer-events-none fixed right-6 bottom-6 z-[60]">
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
            class="border-grey-100 text-grey-900 hover:bg-grey-50 relative cursor-pointer rounded-full border bg-white p-3 shadow-lg"
        >
            <x-chief::icon.bell class="size-5" />

            <div
                x-show="closedNotifications > 0"
                class="from-primary-500 to-primary-600 absolute -right-2 -bottom-2 flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br"
            >
                <span class="text-xs leading-none font-bold text-white" x-text="closedNotifications"></span>
            </div>
        </div>
    </div>
</div>
