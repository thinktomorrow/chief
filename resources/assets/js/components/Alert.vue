<template>
    <transition name="modal" mode="in-out" appear>
        <div v-show="isVisible" class="relative" :class="typeclass">
            <div class="p-2">
                <slot></slot>
            </div>
            <span class="alert__btn absolute top-0 right-0 m-2" @click="isVisible = false">
            	<svg width="18" height="18"><use xlink:href="#x"/></svg>
			</span>
        </div>
    </transition>
</template>

<script>
    export default {
	    props: {
		    'type': {default: 'success'},
	    },
	    data() {
		    return {
			    isVisible: true,
		    }
	    },
	    computed: {
		    typeclass: function() {
			    return 'alert--' + this.type
		    }
	    }
    }
</script>
