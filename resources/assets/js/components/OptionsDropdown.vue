<template>
    <dropdown>
        <span :class="btn_class" slot="trigger" slot-scope="{ toggle }" @click="toggle" v-html="btn_name"></span>
        <div class="dropdown-box">
            <slot></slot>
        </div>
    </dropdown>
</template>
<script>
    export default{
        props: {
            btn_class: {default: 'icon icon-more-vertical'},
            btn_name: {default: ''}
        },
    }
</script>
