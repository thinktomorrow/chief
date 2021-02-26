<template>
    <dropdown>
        <button :class="btn_class" slot="trigger" slot-scope="{ toggle }" @click="toggle" v-html="btn_name"></button>
        <div class="dropdown-box">
            <slot></slot>
        </div>
    </dropdown>
</template>
<script>
    export default{
        props: {
            btn_class: {default: 'btn btn-primary'},
            btn_name: {default: ''}
        },
    }
</script>
