<template>
    <div :id="this.id" v-show="isVisible" class="fixed inset-0 z-30" :class="typedclass">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="absolute inset-0 bg-black opacity-25 cursor-pointer" @click="close"></div>

            <div class="relative p-6 bg-white rounded-2xl shadow-window w-full" :class="sizeClass">
                <div class="space-y-6">
                    <div v-if="title">
                        <span class="text-sm font-semibold tracking-widest uppercase text-grey-500">
                            {{ title }}
                        </span>
                    </div>

                    <div class="prose prose-dark">
                        <slot></slot>
                    </div>
                </div>

                <div v-if="showFooter" class="flex items-center mt-8 space-x-4">
                    <slot name="footer">
                        <slot name="modal-action-buttons"></slot>

                        <a @click="close" class="btn btn-primary-outline">
                            <slot name="modal-close-btn">Annuleren</slot>
                        </a>
                    </slot>
                </div>

                <button
                    type="button"
                    @click="close"
                    class="absolute -top-3 -right-3 p-1 bg-white rounded-full link link-grey icon-label m-7"
                >
                    <svg class="icon-label-icon" width="20" height="20"><use xlink:href="#x" /></svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: { required: true },
        active: { default: false, type: Boolean },
        title: { default: '' },
        type: { default: 'modal' },
        size: { default: 'small' },
    },
    data() {
        return {
            isVisible: false,
            showFooter: true,
            typedclass: this.type == 'sidebar-large' ? 'sidebar sidebar-large' : this.type,
            typedtransition: this.type == 'sidebar-large' ? 'sidebar' : this.type,
            sizeClass: this.getSizeClass(),
        };
    },
    methods: {
        open: function () {
            this.isVisible = true;
        },
        close: function () {
            this.isVisible = false;
            if (this.$el.querySelector('[data-delete-confirmation]'))
                this.$el.querySelector('[data-delete-confirmation]').value = '';
        },
        getSizeClass: function () {
            switch (this.size) {
                case 'small':
                    return 'max-w-xl';
                case 'large':
                    return 'max-w-3xl';
                case 'xl':
                    return 'lg:w-3/4 2xl:w-1/2';
                case 'max':
                    return 'max-w-full';
                default:
                    return 'max-w-xl';
            }
        },
    },
    created() {
        Eventbus.$on('open-modal', (id) => {
            if (this.id == id) {
                this.open();
            }
        });
        Eventbus.$on('close-modal', (id) => {
            if (this.id == id) {
                this.close();
            }
        });

        // Hide the footer slot for sidebar if nothing is explicitly given
        if ((this.type == 'sidebar' || this.type == 'sidebar-large') && !this.$slots.footer) {
            this.showFooter = false;
        }
    },
    mounted() {
        // Emit the 'open-modal' event in case the modal is set to open on pageload
        if (this.active === true) Eventbus.$emit('open-modal', this.id);

        // Listen to keydown to close modal on escape
        document.addEventListener('keydown', (e) => {
            if (this.isVisible && e.keyCode == 27) {
                this.close();
            }
        });
    },
};
</script>
