<template>
    <toggle v-on:toggle-show="createDropdownElement" v-on:toggle-hide="destroyDropdownElement">
        <template slot="trigger" slot-scope="{ toggle, isActive }">
            <span class="inline-block" ref="triggerEl" @click="toggle()">
                <slot name="trigger"></slot>
            </span>
        </template>
        <template slot="target" slot-scope="{ toggle, isActive }">
            <transition name="fade">
                <div ref="targetEl" v-if="isActive" class="panel panel-default --raised inset" style="margin-top:.3em; background-color:white;">
                    <slot></slot>
                </div>
            </transition>
        </template>
    </toggle>
</template>
<script>
    import Popper from 'popper.js';
    export default{
        data(){
            return {
                popper: null,
            }
        },
        methods:{
            createDropdownElement(){
                this.$nextTick(()=>{
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
