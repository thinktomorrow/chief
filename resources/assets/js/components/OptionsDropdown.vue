<template>
    <dropdown>
        <span slot="trigger" slot-scope="{ toggle }" @click="toggle">
            <svg width="18" height="18"><use xlink:href="#dropdown"/></svg>
        </span>
        <div class="dropdown-box">
            <slot></slot>
        </div>
    </dropdown>
</template>
<script>
    export default{
        props: {
            // btn_class: {default: 'block'},
            // btn_name: {default: ''}
        },
    }
</script>
