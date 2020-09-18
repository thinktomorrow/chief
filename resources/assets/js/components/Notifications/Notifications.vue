<template>
    <div class="fixed bottom-0 right-0 flex justify-end items-end min-w-lg max-w-3xl p-4 space-x-4 z-50">
        <div class="flex flex-col items-end space-y-4">
            <!-- Default slot to add on page load notifications e.g. errors -->
            <slot></slot>

            <notification
                v-for="notification in notifications"
                v-bind:key="notification._uid"
                v-bind:type="notification.type"
                v-bind:description="notification.description"
            ></notification>
        </div>

        <div @click="toggleNotifications" v-show="createdNotifications.length > 0" class="relative rounded-full p-4 bg-white border border-grey-100 shadow-lg cursor-pointer hover:bg-grey-50 pop">
            <svg width="16" height="16"><use xlink:href="#home"></use></svg>
            <div v-show="amountOfOpenedNotifications < this.createdNotifications.length" class="absolute top-0 right-0 w-6 h-6 -m-1 leading-none flex justify-center items-center bg-tertiary-500 rounded-full pop">{{ this.createdNotifications.length - amountOfOpenedNotifications }}</div>
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
            }
        },
        created() {
            Eventbus.$on('create-notification', (type, description) => {
                this.createNotification(type, description);
            });
            Eventbus.$on('open-notification', (element) => {
                if(this.createdNotifications.find(notification => notification.element._uid === element._uid)) return;

                this.createdNotifications.push({
                    element: element,
                    opened: true
                });

                this.amountOfOpenedNotifications++;
            })
            Eventbus.$on('hide-notification', (_uid) => {
                this.createdNotifications.find(notification => notification._uid = _uid).opened = false;

                this.amountOfOpenedNotifications--;
            })
        },
        methods: {
            createNotification: function(type, description) {
                this.notifications.push({
                    type,
                    description
                });
            },
            showNotifications: function() {
                this.createdNotifications.forEach(notification => {
                    notification.element.showNotification();
                    notification.opened = true;
                });

                this.amountOfOpenedNotifications = this.createdNotifications.length;
            },
            hideNotifications: function() {
                this.createdNotifications.forEach(notification => {
                    notification.element.hideNotification();
                    notification.opened = false;
                });

                this.amountOfOpenedNotifications = 0;
            },
            toggleNotifications: function () {
                this.amountOfOpenedNotifications > 0 ? this.hideNotifications() : this.showNotifications();
            },
        }
    }
</script>
