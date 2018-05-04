<template>
    <div ref="parent">
        <slot name="trigger" :toggle="toggle"></slot>
        <transition name="fade">
            <div v-show="isActive">
                <slot :toggle="toggle"></slot>
            </div>
        </transition>
    </div>
</template>
<script>
    import Popper from 'popper.js';

    export default{
        mounted(){

            // We assume that the component contains 3 child element where the first one
            // is the backdrop, the second trigger element and the last one the dropdown content itself
            this.triggerEl = this.$refs.parent.children[0];
            this.targetEl = this.$refs.parent.children[1];
        },
        data(){
            return {
                popper: null,
                isActive: false,
                isClosing: false, // Flag to check is dropdown is still in action of closing or not
                triggerEl: null,
                targetEl: null,
            }
        },
        methods: {
            toggle(){

                // Keep local state and only sync with our component state when
                // we know for sure that the toggle action is validated.
                let isActive = !this.isActive;

                if(isActive){
                    if(!this.popper && !this.isClosing){
                        this.isActive = isActive;
                        this.createDropdownElement();
                    };
                } else{
                    if(this.popper && !this.isClosing){
                        this.isActive = isActive;
                        this.destroyDropdownElement();
                    }
                }
            },
            createDropdownElement(){

                this.$nextTick(()=>{
                    this.popper = new Popper(
                        this.triggerEl,
                        this.targetEl,
                        {
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
