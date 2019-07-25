<template>
    <div ref="parent" class="cursor-pointer">
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
                if(this._uid == id){
                    this.toggle();
                }
                else if(this.isActive){
                    this.close();
                }
            });
            Eventbus.$on('close-dropdown',(id) => {
                if(this._uid == id){
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
            if(this.isActive === true) Eventbus.$emit('open-dropdown',this._uid);

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

                if(this.isActive) return;
                
                if(!this.popper && !this.isClosing){
                    this.isActive = true;
                    Eventbus.$emit('open-dropdown',this._uid);
                    this.createDropdownElement();

                    document.addEventListener("click", this.closeDropdownClickEvent, false);
                };
            },
            close(){
                if(this.popper && !this.isClosing){
                    this.isActive = false;
                    this.destroyDropdownElement();

                    document.removeEventListener("click", this.closeDropdownClickEvent, false);
                }
            },
            toggle(event){
                
                // Prevents click event to trigger closeDropdownClickEvent on creation
                if(event) event.stopImmediatePropagation();

                if(!this.isActive) {
                    this.open();
                } else {
                    this.close();
                }
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
//                            placement: 'bottom-start',

                        }
                    );

                    // Force update to let preventOverflow kick in
                    this.popper.scheduleUpdate();
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

            },
            closeDropdownClickEvent(event){
                if(!event.target.matches('.dropdown-box') && !this.isClosing) {
                    this.close();
                }
            },
        },
    }
</script>
