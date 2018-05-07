<template>
    <dropdown>
        <button :class="btn_class" slot="trigger" slot-scope="{ toggle }" @click="toggle">{{ btn_name }}</button>
        <div class="dropdown-target-default">
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
