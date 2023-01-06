<template>
    <dropdown>
        <span
            slot="trigger"
            slot-scope="{ toggle }"
            @click="toggle"
            class="inline-flex items-center p-1.5 rounded-xl link space-x-2 hover:child-svg-scale-110 transition-all duration-75 ease-in-out shadow-card link-grey hover:link-primary hover:bg-primary-50"
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
