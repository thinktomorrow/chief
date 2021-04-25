<template>
    <!-- TODO: fix transition -->
    <!-- TODO: modal should open on form error inside of modal (e.g. delete input field) -->
    <!-- <transition :name="typedtransition" mode="in-out" appear> -->
    <div v-show="isVisible" class="fixed inset-0 z-10" :class="typedclass">
        <div class="absolute inset-0 flex justify-center items-center">
            <div class="absolute inset-0 opacity-25 bg-black cursor-pointer" @click="close"></div>

            <div class="relative window window-white shadow-xl max-w-xl">
                <div class="space-y-6">
                    <div v-if="title">
                        <span class="text-sm uppercase font-semibold text-grey-500 tracking-widest">
                            {{ title }}
                        </span>
                    </div>

                    <div class="prose prose-dark">
                        <slot></slot>
                    </div>
                </div>

                <div v-if="showFooter" class="flex items-center space-x-4 mt-8">
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
                    class="absolute top-0 right-0 link link-grey icon-label bg-white rounded-full m-7 p-1"
                >
                    <svg class="icon-label-icon" width="20" height="20"><use xlink:href="#x" /></svg>
                </button>
            </div>
        </div>
    </div>
    <!-- </transition> -->
</template>

<script>
export default {
    props: {
        id: { required: true },
        active: { default: false, type: Boolean },
        title: { default: '' },
        type: { default: 'modal' },
    },
    data() {
        return {
            isVisible: false,
            showFooter: true,
            typedclass: this.type == 'sidebar-large' ? 'sidebar sidebar-large' : this.type,
            typedtransition: this.type == 'sidebar-large' ? 'sidebar' : this.type,
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
