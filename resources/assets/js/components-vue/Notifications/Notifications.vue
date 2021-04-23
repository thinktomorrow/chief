<template>
    <div class="fixed bottom-0 right-0 flex justify-end items-end min-w-lg max-w-3xl p-6 space-x-4 z-50">
        <div class="flex flex-col items-end space-y-4">
            <!-- Default slot to add on page load notifications e.g. errors -->
            <slot></slot>

            <notification
                v-for="notification in notifications"
                v-bind:key="notification._uid"
                v-bind:type="notification.type"
                v-bind:description="notification.description"
                v-bind:duration="notification.duration"
            ></notification>
        </div>

        <div
            @click="toggleNotifications"
            v-show="createdNotifications.length > 0"
            class="relative rounded-full p-3 bg-white border border-grey-100 shadow-lg cursor-pointer hover:bg-grey-50 pop"
        >
            <svg width="20" height="20" class="icon-label-icon"><use xlink:href="#icon-bell"></use></svg>

            <div
                v-show="amountOfOpenedNotifications < this.createdNotifications.length"
                class="absolute bottom-0 right-0 w-6 h-6 -mr-2 -mb-2 flex justify-center items-center bg-gradient-to-br from-primary-500 to-primary-600 rounded-full pop"
            >
                <span class="text-white text-xs font-bold leading-none">
                    {{ this.createdNotifications.length - amountOfOpenedNotifications }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            notifications: [],
            createdNotifications: [],
            amountOfOpenedNotifications: 0,
        };
    },
    created() {
        Eventbus.$on('create-notification', (type, description, duration = 5000) => {
            this.createNotification(type, description, duration);
        });
        Eventbus.$on('open-notification', (element) => {
            if (this.createdNotifications.find((notification) => notification.element._uid === element._uid)) return;

            this.createdNotifications.push({
                element: element,
                opened: true,
            });

            this.amountOfOpenedNotifications++;
        });
        Eventbus.$on('hide-notification', (_uid) => {
            this.createdNotifications.find((notification) => (notification._uid = _uid)).opened = false;

            this.amountOfOpenedNotifications--;
        });
    },
    methods: {
        createNotification: function (type, description, duration) {
            this.notifications.push({
                type,
                description,
                duration,
            });
        },
        showNotifications: function () {
            this.createdNotifications.forEach((notification) => {
                notification.element.showNotification();
                notification.opened = true;
            });

            this.amountOfOpenedNotifications = this.createdNotifications.length;
        },
        hideNotifications: function () {
            this.createdNotifications.forEach((notification) => {
                notification.element.hideNotification();
                notification.opened = false;
            });

            this.amountOfOpenedNotifications = 0;
        },
        toggleNotifications: function () {
            this.amountOfOpenedNotifications > 0 ? this.hideNotifications() : this.showNotifications();
        },
    },
};
</script>
