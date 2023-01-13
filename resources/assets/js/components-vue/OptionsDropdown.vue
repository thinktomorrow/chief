<template>
    <dropdown>
        <span
            slot="trigger"
            slot-scope="{ toggle }"
            @click="toggle"
            class="inline-flex items-center p-1.5 rounded-xl link hover:child-svg-scale-110 transition-all duration-75 ease-in-out link-grey hover:link-primary hover:bg-primary-50 hover:shadow-card"
        >
            <svg width="18" height="18"><use xlink:href="#icon-ellipsis-vertical" /></svg>
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
