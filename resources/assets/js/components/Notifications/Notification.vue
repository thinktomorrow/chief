<template>
    <div :ref="'notification-' + this._uid" v-show="isVisible" class="flex items-center bg-white border border-grey-100 rounded-lg shadow-lg px-6 py-4 space-x-8 pop origin-right">
        <div class="rounded-full" :class="color">
            <svg width="24" height="24"><use :xlink:href="'#' + this.type"></use></svg>
        </div>

        <div class="flex-grow">
            <p class="text-grey-500">
                <slot>
                    <!-- Notifications that were created asynchronously, have their content stored in description variable -->
                    {{ this.description }}
                </slot>
            </p>
        </div>

        <div @click="hideNotification" class="cursor-pointer rounded-full p-1 text-grey-500 bg-grey-50 hover:bg-grey-100 transition duration-150 ease-in-out">
            <svg width="16" height="16"><use xlink:href="#x"></use></svg>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            description: String,
            type: String,
        },
        data() {
            return {
                isVisible: false,
                color: this.setColorByType()
            }
        },
        mounted() {
            this.showNotification();
        },
        methods: {
            showNotification: function() {
                this.isVisible = true;
                Eventbus.$emit('open-notification', this);
            },
            hideNotification: function() {
                this.isVisible = false;
                Eventbus.$emit('hide-notification', this._uid);
            },
            setColorByType: function() {
                switch(this.type) {
                    case 'success':
                        return 'bg-success bg-opacity-25 text-success';
                        break;
                    case 'error':
                        return 'bg-error bg-opacity-25 text-error';
                        break;
                    case 'warning':
                        return 'bg-warning bg-opacity-25 text-warning';
                        break;
                    case 'information':
                        return 'bg-information bg-opacity-25 text-information';
                        break;
                    default:
                        return 'bg-information bg-opacity-25 text-information';
                        break;
                }
            }
        }
    }
</script>
