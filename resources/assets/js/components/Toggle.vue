<template>
    <div>
        <slot name="trigger"
              :toggle="toggle"
              :isActive="isActive"
        ></slot>
        <slot name="target"
              :toggle="toggle"
              :isActive="isActive"
        ></slot>
    </div>
</template>
<script>

    export default {
        data(){
            return {
                isActive: false,
            }
        },
        methods: {
            toggle(){
                this.isActive = !this.isActive;

                if(this.isActive){
                    this.$emit('toggle-show');
                } else{
                    this.$emit('toggle-hide');
                }
            },
        },
    };
</script>
