<template>
    <popper trigger="click" :options="{placement: 'top'}">
        <div class="popper">
            Popper Content
          </div>

        <button slot="reference">
            Reference Element
          </button>
    </popper>q
</template>
<script>
    import Popper from 'vue-popperjs';
    export default{
        components:{
            'popper': Popper
        },
        data(){
            return {
                popper: null,
                isActive: false,
            }
        },
        methods: {
            toggle(){
                this.isActive = !this.isActive;

                if(this.isActive){
                    this.createDropdownElement();
                } else{
                    this.destroyDropdownElement();
                }
            },
            createDropdownElement(){
                this.$nextTick(()=>{
                    console.log( this.$refs.triggerEl);
                    this.popper = new Popper(
                        this.$refs.triggerEl,
                        this.$refs.targetEl,
                        {
                            removeOnDestroy: true, // let popper handle removal of DOM itself to avoid flicker
//                            placement: 'bottom',
                        }
                    );
                });
            },
            destroyDropdownElement(){
                this.$nextTick(()=>{
                    this.popper.destroy();
                });

            }
        },
        mounted(){

        }
    }
</script>
