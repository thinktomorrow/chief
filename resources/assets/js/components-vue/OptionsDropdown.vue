<template>
    <dropdown>
        <span
            slot="trigger"
            slot-scope="{ toggle }"
            @click="toggle"
            class="inline-flex items-center p-1.5 rounded-xl space-x-2 bg-primary-50 hover:child-svg-scale-110"
        >
            <svg width="18" height="18"><use xlink:href="#icon-dots-vertical" /></svg>
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
