<template>
    <div ref="parent">
        <div v-if="isActive" class="dropdown-backdrop" @click="close"></div>
        <slot name="trigger" :toggle="toggle" :isActive="isActive"></slot>
        <transition name="fade">
            <div v-show="isActive" style="z-index: 1;">
                <slot :toggle="toggle"></slot>
            </div>
        </transition>
    </div>
</template>
<script>
    import Popper from 'popper.js';

    export default{
        created(){

            Eventbus.$on('open-dropdown',(id) => {
                if(this.id == id){
                    this.toggle();
                }
            });
            Eventbus.$on('close-dropdown',(id) => {
                if(this.id == id){
                    this.close();
                }
            });
        },
        mounted(){

            // We assume that the component contains 3 child element where the first one
            // is the backdrop, the second trigger element and the last one the dropdown content itself
            this.triggerEl = this.$refs.parent.children[0];
            this.targetEl = this.$refs.parent.children[1];

            // Emit the 'open-dropdown' event in case the dropdown is set to open on pageload
            if(this.isActive === true) Eventbus.$emit('open-dropdown',this.id);

            // Listen to keydown to close modal on escape
            document.addEventListener("keydown", (e) => {
                if (this.isActive && e.keyCode == 27) {
                    this.toggle();
                }
            });
        },
        props: {
            active: {default: false, type: Boolean }
        },
        data(){
            return {
                popper: null,
                isActive: this.active,
                isClosing: false, // Flag to check is dropdown is still in action of closing or not
                triggerEl: null,
                targetEl: null,
            }
        },
        methods: {
            open(){
                if(!this.popper && !this.isClosing){
                    this.isActive = true;
                    this.createDropdownElement();
                };
            },
            close(){
                if(this.popper && !this.isClosing){
                    this.isActive = false;
                    this.destroyDropdownElement();
                }
            },
            toggle(){

                !this.isActive
                    ? this.open()
                    : this.close();

            },
            createDropdownElement(){

                this.$nextTick(()=>{
                    this.popper = new Popper(
                        this.triggerEl,
                        this.targetEl,
                        {
                            modifiers: {
                                preventOverflow: {
                                    enabled: true,
                                    padding: 5,
                                    boundariesElement: 'viewport'
                                },
                            },
                            removeOnDestroy: false, // We need to keep our element
                            placement: 'bottom-start',

                        }
                    );
                });
            },
            destroyDropdownElement(){

                this.isClosing = true;

                this.$nextTick(()=>{
                    setTimeout(()=>{
                        this.popper.destroy();
                        this.popper = null;
                        this.isClosing = false;
                    }, 500);
                });

            }
        },
    }
</script>
