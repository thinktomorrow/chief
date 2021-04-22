<template>
    <dropdown>
        <span slot="trigger" slot-scope="{ toggle }" @click="toggle" class="icon-label">
            <svg width="20" height="20" class="icon-label-icon"><use xlink:href="#icon-dots-vertical" /></svg>
        </span>

        <div>
            <slot></slot>
        </div>
    </dropdown>
</template>

<script>
export default {
    props: {},
};
</script>
