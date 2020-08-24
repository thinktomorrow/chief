<template>
    <div class="fixed bottom-0 right-0 flex flex-col min-w-md max-w-md space-y-4 m-4 z-50">
        <!-- Default slot to add on page load notifications e.g. errors -->
        <slot></slot>

        <notification
            v-for="notification in notifications"
            v-bind:key="notification._uid"
            v-bind:type="notification.type"
            v-bind:title="notification.title"
            v-bind:description="notification.description"
        ></notification>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                notifications: [
                    // {
                    //     title: "A notification",
                    //     description: "I'm notifying you about something very important. You should really check it out!"
                    // }
                ]
            }
        },
        created() {
            Eventbus.$on('create-notification', (type, title, description) => {
                this.createNotification(type, title, description);
            });
        },
        methods: {
            createNotification(type, title, description) {
                this.notifications.push({
                    type,
                    title,
                    description
                })
            }
        }
    }
</script>
