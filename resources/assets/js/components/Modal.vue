<template>
    <transition :name="typedtransition" mode="in-out" appear>
        <div v-show="isVisible" class="modal" :class="typedclass">
            <div class="modal-backdrop" @click="close"></div>
            <div class="modal-content">
                <div class="panel panel-default">
                    <div class="inset-l">
                        <div class="modal-header">
                            <h3>{{ title }}</h3>
                        </div>

                        <div class="modal-body">
                            <slot></slot>
                        </div>
                    </div>

                    <div v-if="showFooter" class="modal-footer inset-l center-y inline-group">
                        <slot name="footer">
                            <a @click="close" class="btn btn-o-primary"><slot name='modal-close-btn'>Annuleer</slot></a>
                            <slot name='modal-action-buttons'></slot>
                        </slot>
                    </div>

                </div>
            </div>
            <button @click="close" class="modal-close"><i class="icon icon-x"></i></button>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        id: { required: true },
        active: { default: false, type: Boolean, },
        title: { default: '' },
        type: { default: 'modal' },
    },
    data(){
        return {
            isVisible: false,
            showFooter: true,
            typedclass: this.type == 'sidebar-large' ? 'sidebar sidebar-large' : this.type,
            typedtransition: this.type == 'sidebar-large' ? 'sidebar' : this.type,
        }
    },
    methods:{
        open: function(){
            this.isVisible = true;
        },
        close: function(){
            this.isVisible = false;
        },

    },
    created(){

        Eventbus.$on('open-modal',(id) => {
            if(this.id == id){
                this.open();
            }
        });
        Eventbus.$on('close-modal',(id) => {
            if(this.id == id){
                this.close();
            }
        });

        // Hide the footer slot for sidebar if nothing is explicitly given
        if((this.type == 'sidebar' || this.type == 'sidebar-large') && !this.$slots.footer)
        {
            this.showFooter = false;
        }

    },
    mounted: function () {

        // Emit the 'open-modal' event in case the modal is set to open on pageload
        if(this.active === true) Eventbus.$emit('open-modal',this.id);

        // Listen to keydown to close modal on escape
        document.addEventListener("keydown", (e) => {
            if (this.isVisible && e.keyCode == 27) {
                this.close();
            }
        });
    }

};
</script>
