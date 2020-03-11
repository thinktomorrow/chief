<template>
    <transition name="modal" mode="in-out" appear>
        <div v-show="isVisible" :class="typeclass">
            <div class="alert__copy inline">
                <slot></slot>
            </div>
            <span class="alert__btn icon icon-x" @click="isVisible = false"></span>
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
