<template>
    <div class="fixed bottom-0 right-0 flex justify-end items-end min-w-lg max-w-3xl p-4 space-x-4 z-50">
        <div ref="notificationWrapper" class="flex flex-col items-end space-y-4">
            <!-- Default slot to add on page load notifications e.g. errors -->
            <slot></slot>

            <notification
                v-for="notification in notifications"
                v-bind:key="notification._uid"
                v-bind:type="notification.type"
                v-bind:description="notification.description"
            ></notification>
        </div>

        <div @click="showMinimizedNotifications" class="relative rounded-full p-4 bg-white border border-grey-100 shadow-lg cursor-pointer">
            <svg width="16" height="16"><use xlink:href="#home"></use></svg>
            <div v-show="minimizedNotifications.length > 0" class="absolute top-0 right-0 w-6 h-6 -m-1 leading-none flex justify-center items-center bg-secondary-500 rounded-full">{{ minimizedNotifications.length }}</div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                notifications: [],
                minimizedNotifications: [],
            }
        },
        created() {
            Eventbus.$on('create-notification', (type, description) => {
                this.createNotification(type, description);
            });
            Eventbus.$on('minimize-notification', (element) => {
                this.minimizedNotifications.push(element);
            })
        },
        methods: {
            createNotification(type, description) {
                this.notifications.push({
                    type,
                    description
                });
            },
            showMinimizedNotifications() {
                this.minimizedNotifications.forEach(element => element.showNotification());
                this.minimizedNotifications = [];
            }
        }
    }
</script>
