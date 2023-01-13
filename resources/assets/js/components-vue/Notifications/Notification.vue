<template>
    <transition name="pop">
        <div
            v-show="isVisible"
            :ref="'notification-' + this._uid"
            class="flex items-center px-6 py-4 space-x-6 origin-right bg-white border rounded-lg shadow-lg border-grey-100"
        >
            <div class="rounded-full" :class="color">
                <svg width="24" height="24"><use :xlink:href="iconId"></use></svg>
            </div>

            <div class="grow">
                <p class="font-medium text-grey-500">
                    <slot>
                        <!-- Notifications that were created asynchronously, have their content stored in description variable -->
                        {{ description }}
                    </slot>
                </p>
            </div>

            <div @click="hideNotification" class="cursor-pointer link link-grey icon-label">
                <svg width="18" height="18" class="icon-label-icon"><use xlink:href="#icon-x-mark"></use></svg>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        type: String,
        description: String,
        duration: [Number, String],
    },
    data() {
        return {
            isVisible: false,
            color: this.setColorByType(),
            iconId: this.getIconId(),
        };
    },
    mounted() {
        this.showNotification(true);
    },
    methods: {
        showNotification: function (isClosingAutomatically = false) {
            this.isVisible = true;
            Eventbus.$emit('open-notification', this);

            if (isClosingAutomatically && this.type !== 'error') {
                setTimeout(() => {
                    this.hideNotification();
                }, parseInt(this.duration, 10));
            }
        },
        hideNotification: function () {
            this.isVisible = false;
            Eventbus.$emit('hide-notification', this._uid);
        },
        setColorByType: function () {
            switch (this.type) {
                case 'success':
                    return 'bg-green-50 text-green-500';
                case 'error':
                    return 'bg-red-50 text-red-500';
                case 'warning':
                    return 'bg-orange-50 text-orange-500';
                case 'information':
                case 'info':
                    return 'bg-blue-50 text-blue-500';
                default:
                    return 'bg-blue-50 text-blue-500';
            }
        },
        getIconId: function () {
            switch (this.type) {
                case 'success':
                    return '#icon-check-circle';
                case 'error':
                    return '#icon-exclamation-circle';
                case 'warning':
                    return '#icon-exclamation-triangle';
                case 'information':
                case 'info':
                    return '#icon-information-circle';
                default:
                    return '#icon-information-circle';
            }
        },
    },
};
</script>
